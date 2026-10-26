<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reimbursement_rules {
    public $roles = array('accounting' => 'Accounting', 'budget' => 'Budget', 'approver' => 'Approving Officer', 'cashier' => 'Cashier');
    public $stages = array('Submitted' => array('accounting', 'For Budget Certification'), 'For Budget Certification' => array('budget', 'For Approval'), 'For Approval' => array('approver', 'For Payment'), 'For Payment' => array('cashier', 'Paid'));
    public $documents = array('authority'=>'Approved travel authority/order', 'itinerary'=>'Approved itinerary of travel', 'completion'=>'Certificate of travel completed and appearance/attendance', 'expenses'=>'Expense evidence: tickets, invoices/receipts, or applicable RER/CERR', 'conditional'=>'Applicable supporting documents: boarding passes, lodging invoices, revised itinerary, excess-rate authorization, and other office requirements');
    public function cents($value) {
        if (!is_scalar($value) || !preg_match('/^\d{1,8}(\.\d{1,2})?$/D', (string) $value)) throw new InvalidArgumentException('Enter a non-negative amount with at most two decimal places.');
        $parts = explode('.', (string) $value);
        return (int) $parts[0] * 100 + (int) str_pad($parts[1] ?? '', 2, '0');
    }
    public function expenses($rows) {
        if (!is_array($rows) || count($rows) > 100) throw new InvalidArgumentException('Enter up to 100 expense lines.');
        $items = array(); $total = 0;
        foreach ($rows as $row) {
            if (!is_array($row)) throw new InvalidArgumentException('Invalid expense line.');
            $description = trim((string) ($row['description'] ?? ''));
            $amount = trim((string) ($row['amount'] ?? ''));
            if ($description === '' && ($amount === '' || $amount === '0' || $amount === '0.00')) continue;
            $cents = $this->cents($amount);
            $date = (string) ($row['date'] ?? '');
            $parsed = DateTime::createFromFormat('!Y-m-d', $date);
            if (!$parsed || $parsed->format('Y-m-d') !== $date || $description === '' || strlen($description) > 500 || $cents <= 0) throw new InvalidArgumentException('Each expense needs a valid date, description (up to 500 characters), and positive amount.');
            $category = (string) ($row['category'] ?? '');
            if (!in_array($category, array('Transportation', 'Daily travel expenses', 'Other authorized expenses'), TRUE)) throw new InvalidArgumentException('Invalid expense category.');
            $reference = trim((string) ($row['reference'] ?? ''));
            if (strlen($reference) > 200) throw new InvalidArgumentException('Receipt references must be at most 200 characters.');
            $items[] = array('date'=>$date,'category'=>$category,'description'=>$description,'reference'=>$reference,'amount'=>number_format($cents / 100, 2, '.', ''));
            $total += $cents;
        }
        if ($total > 9999999999) throw new InvalidArgumentException('Total exceeds the supported amount.');
        return array($items, $total);
    }
    public function itinerary_rows($rows) {
        if (!is_array($rows) || count($rows) > 80) throw new InvalidArgumentException('Enter up to 80 itinerary rows.');
        $items = array(); $total = 0;
        foreach ($rows as $row) {
            if (!is_array($row)) throw new InvalidArgumentException('Invalid itinerary row.');
            $clean = array();
            foreach (array('date','date_to','places','departure','arrival','means','reference') as $key) {
                if (isset($row[$key]) && !is_scalar($row[$key])) throw new InvalidArgumentException('Invalid itinerary field.');
                $clean[$key] = trim((string) ($row[$key] ?? ''));
            }
            $lineTotal = 0;
            foreach (array('transportation','incidental','per_diem') as $key) {
                $value = $row[$key] ?? '';
                $cents = $this->cents($value === '' ? '0' : $value);
                $lineTotal += $cents;
                $clean[$key] = number_format($cents / 100, 2, '.', '');
            }
            if ($lineTotal === 0 && implode('', array_slice($clean, 0, 7)) === '') continue;
            foreach (array('date','date_to') as $key) {
                if ($key === 'date_to' && $clean[$key] === '') { $clean[$key] = $clean['date']; continue; }
                $parsed = DateTime::createFromFormat('!Y-m-d', $clean[$key]);
                if (!$parsed || $parsed->format('Y-m-d') !== $clean[$key]) throw new InvalidArgumentException('Each itinerary row needs a valid date or date range.');
            }
            if ($clean['date_to'] < $clean['date']) throw new InvalidArgumentException('The itinerary end date must be on or after its start date.');
            if ($clean['places'] === '' || strlen($clean['places']) > 500) throw new InvalidArgumentException('Enter places to be visited (up to 500 characters).');
            foreach (array('departure','arrival') as $key) if ($clean[$key] !== '' && !preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $clean[$key])) throw new InvalidArgumentException('Enter valid departure and arrival times.');
            if (strlen($clean['means']) > 100 || strlen($clean['reference']) > 200) throw new InvalidArgumentException('Transport means or evidence reference is too long.');
            $clean['amount'] = number_format($lineTotal / 100, 2, '.', '');
            $items[] = $clean; $total += $lineTotal;
        }
        if ($total > 9999999999) throw new InvalidArgumentException('Total exceeds the supported amount.');
        return array($items, $total);
    }
    public function itinerary_meta($input) {
        if (!is_array($input)) throw new InvalidArgumentException('Invalid itinerary details.');
        $meta = array();
        foreach (array('agency'=>255,'number'=>100,'employee_name'=>255,'purpose'=>2000,'prepared_by'=>255,'certifier_name'=>255,'certifier_position'=>255,'approver_name'=>255,'approver_position'=>255) as $key=>$limit) {
            if (isset($input[$key]) && !is_scalar($input[$key])) throw new InvalidArgumentException('Invalid itinerary detail.');
            $meta[$key] = trim((string)($input[$key] ?? ''));
            if (strlen($meta[$key]) > $limit) throw new InvalidArgumentException('Itinerary field exceeds its length limit: '.$key);
        }
        if ($meta['agency'] === '' || $meta['employee_name'] === '' || $meta['purpose'] === '') throw new InvalidArgumentException('Agency, employee name, and purpose are required.');
        return $meta;
    }
    // Display older category-based claims without losing or inventing amounts.
    public function itinerary_display_rows($rows) {
        $items = array();
        foreach ((array)$rows as $row) {
            if (isset($row['places'])) { $items[] = $row; continue; }
            $item = array('date'=>$row['date'] ?? '', 'date_to'=>$row['date'] ?? '', 'places'=>$row['description'] ?? '', 'departure'=>'', 'arrival'=>'', 'means'=>'', 'reference'=>$row['reference'] ?? '', 'transportation'=>'0.00', 'incidental'=>'0.00', 'per_diem'=>'0.00', 'amount'=>$row['amount'] ?? '0.00');
            $key = ($row['category'] ?? '') === 'Transportation' ? 'transportation' : (($row['category'] ?? '') === 'Daily travel expenses' ? 'per_diem' : 'incidental');
            $item[$key] = $row['amount'] ?? '0.00'; $items[] = $item;
        }
        return $items;
    }
    public function can_review($status, $roles, $owner, $actor) {
        return (string) $owner !== (string) $actor && isset($this->stages[$status]) && in_array($this->stages[$status][0], $roles, TRUE);
    }
    public function submission_errors($travel, $claim) {
        $errors = array();
        if (strtolower(trim((string) $travel->status)) !== 'approved') $errors[] = 'Travel must be approved before submission.';
        $end = substr((string) ($travel->date_end ?? ''), 0, 10);
        $date = DateTime::createFromFormat('!Y-m-d', $end);
        if (!$date || $date->format('Y-m-d') !== $end || $end > (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d')) $errors[] = 'A valid completed travel end date is required.';
        if ($claim['total_cents'] <= $claim['advance_cents']) $errors[] = 'No positive reimbursement balance. Settle cash-advance liquidation/refunds with Accounting.';
        if (empty($claim['document_path'])) $errors[] = 'Upload the supporting-document package.';
        $checks = json_decode($claim['checklist_json'], TRUE) ?: array();
        foreach (array_keys($this->documents) as $key) if (empty($checks[$key])) $errors[] = 'Confirm: ' . $this->documents[$key] . '.';
        return $errors;
    }
}
