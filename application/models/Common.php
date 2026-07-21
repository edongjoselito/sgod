<?php


class Common extends CI_Model
{

    public function __construct()
    {
        $this->load->database();

        // MySQL 5.7+ enables ONLY_FULL_GROUP_BY by default; several legacy
        // queries here expect the older lenient grouping behaviour.  Drop the
        // flag at the session level so those queries continue to work without
        // rewrites.
        $this->db->query("SET SESSION sql_mode = REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', '')");
    }
    public function one_cond_between($table, $col, $val, $con, $minvalue, $maxvalue)
    {
        $this->db->where($col, $val);
        $this->db->where("$con BETWEEN '$minvalue' AND '$maxvalue'");
        $query = $this->db->get($table);
        return $query->result();
    }

    // common functions loop

    public function no_cond($table)
    {
        $query = $this->db->get($table);
        return $query->result();
    }

    public function one_cond($table, $col, $val)
    {
        $this->db->where($col, $val);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function two_cond($table, $col, $val, $col2, $val2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }
    public function three_cond($table, $col, $val, $col2, $val2, $col3, $val3)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond_ob($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4,$ob,$obval)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function five_cond_ob($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4,$col5,$val5,$ob,$obval)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond_or($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $orcol, $orval, $orcol2, $orval2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->or_where($orcol, $orval);
        $this->db->or_where($orcol2, $orval2);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function one_cond_or($table, $col, $val, $orcol, $orval)
    {
        $this->db->where($col, $val);
        $this->db->or_where($orcol, $orval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function one_cond_two_or($table, $col, $val, $orcol, $orval, $orcol2, $orval2)
    {
        $this->db->where($col, $val);
        $this->db->or_where($orcol, $orval);
        $this->db->or_where($orcol2, $orval2);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function five_cond($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $col5, $val5)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function six_cond($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $col5, $val5, $col6, $val6)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $this->db->where($col6, $val6);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_not_equal($table, $col, $val, $col2, $val2, $col3, $val3, $colob, $colobv)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->order_by($colob, $colobv);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond_not_equal($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $colob, $colobv)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4 . ' != ', $val4);
        $this->db->order_by($colob, $colobv);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_not_equal_gb($table, $col, $val, $col2, $val2, $col3, $val3, $colob, $colobv, $gb)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->order_by($colob, $colobv);
        $this->db->group_by($gb);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }



    // one condation order by
    public function no_cond_order_by($table, $orderby, $orderbyvalue)
    {
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }
    public function one_cond_loop_order_by($table, $col, $val, $orderby, $orderbyvalue)
    {
        $this->db->where($col, $val);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function two_cond_order_by($table, $col, $val, $col2, $val2, $orderby, $orderbyvalue)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function one_cond_order_by($table, $col, $val, $orderby, $orderbyvalue)
    {
        $this->db->where($col, $val);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_order_by($table, $col, $val, $col2, $val2, $col3, $val3, $orderby, $orderbyvalue)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_order_by_select($table,$select, $col, $val, $col2, $val2, $col3, $val3, $orderby, $orderbyvalue)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function two_cond_order_by_select($table,$select, $col, $val, $col2, $val2, $orderby, $orderbyvalue)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond_order_by_select($table,$select, $col, $val, $col2, $val2,$col3, $val3,$col4, $val4, $orderby, $orderbyvalue)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_order_by_or($table, $col, $val, $col2, $val2, $col3, $val3, $orderby, $orderbyvalue)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($orderby, $orderbyvalue);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    // one condation group by

    public function no_cond_group($table, $valcol)
    {
        $this->db->group_by($valcol);
        
        $query = $this->db->get($table);
        return $query->result(); 
    }

    public function no_cond_gb($table, $valcol)
    {
        $this->db->group_by($valcol);
        
        $query = $this->db->get($table);
        return $query->result(); 
    }

    public function no_cond_group_ob($table, $valcol,$ob, $obval)
    {
        $this->db->group_by($valcol);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function one_cond_group_ob($table,$col, $val, $valcol,$ob, $obval)
    {
        $this->db->where($col, $val);
        $this->db->group_by($valcol);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function two_cond_group_ob($table,$col, $val, $col1, $val1, $valcol,$ob, $obval)
    {
        $this->db->where($col, $val);
        $this->db->where($col1, $val1);
        $this->db->group_by($valcol);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function one_cond_group($table, $col, $val, $valcol)
    {
        $this->db->where($col, $val);
        $this->db->group_by($valcol);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function two_cond_group($table, $col, $val, $col2, $val2, $valcol)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->group_by($valcol);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function three_cond_group($table, $col, $val, $col2, $val2, $col3, $val3, $gb)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->group_by($gb);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function get_last_row($table, $ob, $obv)
    {
        $query = $this->db->order_by($ob, $obv)->limit(1)->get($table);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }




    // common function single row

    public function one_cond_row($table, $col, $val)
    {
        $this->db->where($col, $val);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function two_cond_row($table, $col, $val, $col2, $val2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function three_cond_row($table, $col, $val, $col2, $val2, $col3, $val3)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function two_cond_not_equal_row($table, $col, $val, $col2, $val2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $result = $this->db->get($table)->row();
        return $result;
    }



    public function three_cond_not_equal_row($table, $col, $val, $col2, $val2, $col3, $val3)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function one_cond_row_desc($table, $col, $val, $ob, $obv)
    {
        $this->db->where($col, $val);
        $this->db->order_by($ob, $obv);
        $result = $this->db->get($table)->row();
        return $result;
    }


    // common function count

    public function no_cond_count_row($table)
    {
        $result = $this->db->get($table);
        return $result;
    }

    public function one_cond_count_row($table, $col, $val)
    {
        $this->db->where($col, $val);
        $result = $this->db->get($table);
        return $result;
    }


    public function two_cond_count_row($table, $col, $val, $col2, $val2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $result = $this->db->get($table);
        return $result;
    }

    public function three_cond_not_equal_count_row($table, $col, $val, $col2, $val2, $col3, $val3)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $result = $this->db->get($table);
        return $result;
    }

    public function one_cond_ne_one_cond_count_row($table, $col, $val, $col2, $val2)
    {
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $result = $this->db->get($table);
        return $result;
    }

    public function three_cond_count_row($table, $col, $val, $col2, $val2, $col3, $val3)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $result = $this->db->get($table);
        return $result;
    }

    public function four_cond_count_row($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $result = $this->db->get($table);
        return $result;
    }
    public function five_cond_count_row($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $col5, $val5)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $result = $this->db->get($table);
        return $result;
    }

    public function four_cond_count_row_one_not_equal($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4 . ' != ', $val4);
        $result = $this->db->get($table);
        return $result;
    }

    //join table

   public function two_join_ob($t1, $t2, $select, $joinby, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_one_cond($t1, $t2, $select, $joinby, $col, $val, $gb, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->group_by($gb);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_one_cond_not_gb($t1, $t2, $select, $joinby, $col, $val, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_three_cond($t1, $t2, $select, $joinby, $col, $val, $col2, $val2, $col3, $val3, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_three_cond_count($t1, $t2, $select, $joinby, $col, $val, $col2, $val2, $col3, $val3, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query;
    }

    public function two_join_four_cond($t1, $t2, $select, $joinby, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }
    

    public function two_join_five_cond($t1, $t2, $select, $joinby, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $col5, $val5, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function three_join_one_cond($t1, $t2, $t3, $select, $joinby, $joinby2, $col, $val, $gb, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->join($t3 . ' as c', $joinby2, 'left');
        $this->db->where($col, $val);
        $this->db->group_by($gb);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_one_ne_cond($t1, $t2, $select, $joinby, $col, $val, $gb, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col . ' != ', $val);
        $this->db->group_by($gb);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_two_cond($t1, $t2, $select, $joinby, $col, $val, $col2, $val2, $ob, $obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_seven_cond($t1, $t2, $select, $joinby, $col, $val, $col2, $val2,$col3, $val3,$col4, $val4,$col5, $val5,$col6, $val6,$col7, $val7,$ob,$obval)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $this->db->where($col6, $val6);
        $this->db->where($col7, $val7);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_two_cond_count($t1, $t2, $select, $joinby, $col, $val, $col2, $val2)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $query = $this->db->get();
        return $query;
    }

    public function two_join_two_cond_gb($t1, $t2, $select, $joinby, $col, $val, $col2, $val2,$gb)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->group_by($gb);
        $query = $this->db->get();
        return $query->result();
    }

    public function two_join_seven_cond_count($t1, $t2, $select, $joinby, $col, $val, $col2, $val2,$col3, $val3,$col4, $val4,$col5, $val5,$col6, $val6,$col7, $val7)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $this->db->where($col5, $val5);
        $this->db->where($col6, $val6);
        $this->db->where($col7, $val7);
        $query = $this->db->get();
        return $query;
    }

    

    public function two_join_two_cond_two_ne_cond_count($t1, $t2, $select, $joinby, $col, $val, $col2, $val2,$col3, $val3,$col4, $val4)
    {
        $this->db->select($select);
        $this->db->from($t1 . ' as a');
        $this->db->join($t2 . ' as b', $joinby, 'left');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->where($col4 . ' != ', $val4);
        $query = $this->db->get();
        return $query;
    }


    public function getStudeProfiles()
    {
        $this->db->where('schoolID', $this->session->username);
        $this->db->order_by('LastName', 'ASC');
        $query = $this->db->get('studeprofile');
        return $query->result();
    }




    //common function

    public function delete($table, $col_id, $segment)
    {
        $id = $this->uri->segment($segment);
        $this->db->where($col_id, $id);
        $this->db->delete($table);
        return true;
    }

    function delete_with_attach($table, $segment, $attach)
    {
        $this->db->where('id', $segment);
        unlink("uploads/" . $attach);
        $this->db->delete($table);
    }

    function delete_with_attachv2($table, $segment, $folder, $attach)
    {
        $this->db->where('id', $segment);
        unlink($folder . "/" . $attach);
        $this->db->delete($table);
    }


    public function tcd($table, $col, $val, $col2, $val2)
    { // two cond delete
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->delete($table);
        return true;
    }

    public function del($table, $col, $val)
    { // one cond delete
        $this->db->where($col, $val);
        $this->db->delete($table);
        return true;
    }

    public function rqa($table, $jobID)
    {
        $query = $this->db->query("SELECT * FROM hris_applications a join $table r on a.appID=r.appID where jobID='" . $jobID . "' and  total_points>='50' ORDER BY total_points DESC");
        return $query->result();
    }

    public function rqa_non($jobID)
    {

        $query = $this->db->query("SELECT * FROM hris_applications a join hris_rating_none r on a.appID=r.appID where jobID='" . $jobID . "'  and dq=1 ORDER BY total_points DESC");
        return $query->result();
    }

    public function check_application($col2, $val2)
    {
        $this->db->select("YEAR(dateSubmitted) AS year, COUNT(*) AS count");
        $this->db->from("hris_applications");
        $this->db->where($col2, $val2);
        $this->db->group_by("year");
        $this->db->order_by("year", "DESC");
        $result = $this->db->get();
        return $result;
    }

    public function one_cond_and_two_cond_ne_ob($table, $col, $val, $col2, $val2, $col3, $val3,$ob,$obval)
    {
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
        
    }

    public function one_cond_and_two_cond_ne_ob_select($table,$select, $col, $val, $col2, $val2, $col3, $val3,$ob,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->order_by($ob, $obval);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
        
    }

    public function two_cond_and_two_cond_ne_ob_select($table,$select, $col, $val, $col2, $val2, $col3, $val3,$col4, $val4,$ob,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3 . ' != ', $val3);
        $this->db->where($col4 . ' != ', $val4);
        $this->db->order_by($ob, $obval);

        $query = $this->db->get($table);

        return $query->result(); 
    }

    public function one_cond_and_one_cond_ne_ob_select($table,$select, $col, $val, $col2, $val2,$ob,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $this->db->order_by($ob, $obval);

        $query = $this->db->get($table);

        return $query->result(); 
        
    }

    public function one_cond_and_one_cond_ne_ob_select_gb($table,$select, $col, $val, $col2, $val2,$ob,$obval,$gb)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2 . ' != ', $val2);
        $this->db->group_by($gb);
        $this->db->order_by($ob, $obval);

        $query = $this->db->get($table);

        return $query->result(); 
        
    }

    public function one_cond_row_select($table,$select, $col, $val)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $result = $this->db->get($table)->row();
        return $result;
    }
    
    public function two_cond_row_select($table,$select, $col, $val,$col2, $val2)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function three_cond_row_select($table,$select, $col, $val,$col2, $val2,$col3, $val3)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $result = $this->db->get($table)->row();
        return $result;
    }

    public function no_cond_select($table,$select)
    {
        $this->db->select($select);
        $query = $this->db->get($table);
        return $query->result();
    }
    public function no_cond_except_select($table, $select)
    {
        $this->db->select($select);
        $this->db->where('position !=', 'Super Admin');
        $query = $this->db->get($table);
        return $query->result();
    }

    

public function no_cond_except($table, $col, $val)
{
    $this->db->where($col.' !=', $val);
    $query = $this->db->get($table);
    return $query->result();
}

    public function no_cond_select_ob($table,$select,$ob,$obval)
    {
        $this->db->select($select);
        $this->db->order_by($ob, $obval);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function one_cond_select($table,$select, $col, $val)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function two_cond_select($table,$select, $col, $val,$col2, $val2)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $query = $this->db->get($table);
        return $query->result();
    }
    

    public function one_cond_select_gb($table,$select, $col, $val,$gb)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->group_by($gb);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function one_cond_select_ob($table,$select, $col, $val,$obcol,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->order_by($obcol, $obval);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function two_cond_select_ob($table,$select, $col, $val, $col2, $val2, $obcol,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->order_by($obcol, $obval);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function three_cond_select_ob($table,$select, $col, $val, $col2, $val2, $col3, $val3, $obcol,$obval)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->order_by($obcol, $obval);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function two_cond_select_gb($table,$select, $col, $val,$col2, $val2,$gb)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->group_by($gb);
        $query = $this->db->get($table);
        return $query->result();
    }
    

    public function three_cond_select($table,$select, $col, $val,$col2,$val2,$col3,$val3)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function three_cond_two_not_equal($table, $col, $val, $col2, $val2, $col3, $val3, $col4, $val4, $col5, $val5)
    {
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4 . ' != ', $val4);
        $this->db->where($col5 . ' != ', $val5);
        
        $query = $this->db->get($table);
        
        return $query->result(); 
    }

    public function four_cond_select($table,$select, $col, $val,$col2,$val2,$col3,$val3,$col4,$val4)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->where($col4, $val4);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function two_cond_select_limit($table,$select, $col, $val,$col2, $val2,$limit)
    {
        $this->db->select($select);
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->limit($limit);
        $query = $this->db->get($table);
        return $query->result();
    }

    function getLevel()
	{
		$this->db->select('Major');
		$this->db->distinct();
		$this->db->order_by('Major', 'ASC');
		$query = $this->db->get('course_table');
		return $query->result();
	}

    public function column_count_three_cond($table,$col,$val,$col2,$val2,$col3,$val3){
        return $this->db->where($col, $val)
                    ->where($col2, $val2)
                    ->where($col3, $val3)
                    ->count_all_results($table);
    }

    public function column_count_four_cond($table,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4){
        return $this->db->where($col, $val)
                    ->where($col2, $val2)
                    ->where($col3, $val3)
                    ->where($col4, $val4)
                    ->count_all_results($table);
    }

    

    // need to erase
    public function two_cond_groupby($table, $group_by_field, $field1, $value1, $field2, $value2)
    {
        $this->db->select($group_by_field);
        $this->db->where($field1, $value1);
        $this->db->where($field2, $value2);
        $this->db->group_by($group_by_field);
        $query = $this->db->get($table);
        return $query->result();
    }

    
    public function two_cond_result($table, $field1, $value1, $field2, $value2)
    {
        $this->db->where($field1, $value1);
        $this->db->where($field2, $value2);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function count_students_by_year_and_sy($col,$val,$col2,$val2)
    {
        $this->db->select('SY, YearLevel, COUNT(*) as total_students');
        $this->db->from('semesterstude');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->group_by(['YearLevel']);
        $this->db->order_by('YearLevel', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function count_students_by_age($col,$val,$col2,$val2,$col3,$val3)
    {
        $this->db->select('age,YearLevel, COUNT(*) as total_students');
        $this->db->from('semesterstude');
        $this->db->where($col, $val);
        $this->db->where($col2, $val2);
        $this->db->where($col3, $val3);
        $this->db->group_by(['age']);
        $this->db->order_by('age', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_applicant_staff($jobID)
    {
        $this->db->select("
        a.*,
        staff.stat AS ren,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    //$this->db->join('hris_applications_rating r', 'a.appID = r.appID', 'left');
    $this->db->where('a.jobID', $jobID);
    $this->db->where('a.dq', 0);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_applicant_staff_endorsed($jobID)
    {
        $this->db->select("
        a.*,
        staff.stat AS ren,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    //$this->db->join('hris_applications_rating r', 'a.appID = r.appID', 'left');
    $this->db->where('a.jobID', $jobID);
    $this->db->where('a.dq', 0);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_applicant_staff_validated($jvStatus,$appStatus,$jobTypes = null, $excludeDq2 = true)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
    $this->db->where('j.jvStatus', 'Open');
    $this->db->where('a.appStatus', $appStatus);
    if ($excludeDq2) {
        $this->db->where('a.dq !=', 2);
    }
        if (!empty($jobTypes)) {
            $this->db->where_in('j.job_type', $jobTypes);
        }
    $this->db->order_by('j.jvStatus', 'asc');

    $query = $this->db->get();
    return $query->result();
    }

    public function get_validated_applicant($appStatus)
        {
            $this->db->select("
            a.*,
            j.jvStatus,j.jobID,j.job_type,j.jobTitle,
            COALESCE(app.record_no, staff.IDNumber) AS code,
            COALESCE(app.id, staff.IDNumber) AS id,
            COALESCE(app.empEmail, staff.IDNumber) AS renren,
            COALESCE(app.contactNo, staff.contactNo) AS contactNo,
            COALESCE(app.FirstName, staff.FirstName) AS FirstName,
            COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
            COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
            COALESCE(app.LastName, staff.LastName) AS LastName,
            COALESCE(app.perProvince, staff.perProvince) AS province,
            COALESCE(app.jhss, staff.jhss) AS jhss,
            COALESCE(app.shss, staff.shss) AS shss,
            COALESCE(app.resCity, staff.resCity) AS resCity,
            COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
            CASE
                WHEN app.record_no IS NOT NULL THEN 'ma'
                WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
                ELSE 'unknown'
            END AS st
        ");
        $this->db->from('hris_applications a');
        $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
        $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
        $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
        $this->db->where('j.jvStatus', 'Open');
        $this->db->where('a.appStatus', $appStatus);

        $this->db->where('a.dq !=', 2);
        
        $this->db->order_by('j.jvStatus', 'asc');

        $query = $this->db->get();
        return $query->result();
        }

    public function get_applicant_staff_dq($jvStatus,$dq)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
    $this->db->where('j.jvStatus', $jvStatus);
    $this->db->where('a.dq', $dq);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_submitted_applicant($jobID)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.resProvince, staff.resProvince) AS province,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        COALESCE(app.age, staff.age) AS age,
        COALESCE(app.Sex, staff.Sex) AS sex,
        COALESCE(app.MaritalStatus, staff.MaritalStatus) AS ms,
        COALESCE(app.empEmail, staff.empEmail) AS email,
        COALESCE(app.empMobile, staff.empMobile) AS cn,
        COALESCE(app.bd, staff.bd) AS bachelor,
        COALESCE(app.religion, staff.religion) AS religion,
        COALESCE(app.disability, staff.disability) AS disability,
        COALESCE(app.ethnicity, staff.ethnicity) AS ethnicity,
        COALESCE(app.csEligibility, staff.csEligibility) AS csEligibility,
        COALESCE(app.bd, staff.bd) AS bd,
        COALESCE(app.master, staff.master) AS master,
        COALESCE(app.doctor, staff.doctor) AS doctor,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'profile_reg_edit'
            WHEN staff.IDNumber IS NOT NULL THEN 'employee_edit'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');

    $this->db->join('hris_rating_request rr', 'rr.app_id = a.appID', 'left');
    $this->db->where('rr.app_id IS NULL', null, false);
    
    $this->db->where('a.jobID', $jobID);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_endorsed_applicant($jvStatus,$dq,$district,$jobID)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
    $this->db->where('j.jvStatus', $jvStatus);
    $this->db->where('a.dq', $dq);
    $this->db->where('a.appStatus', 'Endorsed for Rating');
    $this->db->where('a.district', $district);
    $this->db->where('a.jobID', $jobID);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_endorsed_applicant_with_stat($jvStatus,$dq,$district,$jobID,$status)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
    $this->db->where('j.jvStatus', $jvStatus);
    $this->db->where('a.dq', 0);
    $this->db->where('a.district', $district);
    $this->db->where('a.jobID', $jobID);
    $this->db->where('appStatus', $status);

    $query = $this->db->get();
    return $query->result();
    }

    public function travel_for_action()
    {
        $this->db->select('tr.*, ts.position');
        $this->db->from('travel_requests tr');
        $this->db->join(
            '(SELECT t1.*
            FROM travel_sign_settings t1
            INNER JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM travel_sign_settings
                GROUP BY user_id
            ) t2 ON t1.user_id = t2.user_id AND t1.id = t2.max_id
            ) ts',
            'tr.IDNumber = ts.user_id',
            'left'
        );
        //$this->db->where('tr.status', 'Endorsed');
        
        $query = $this->db->get();

        return $query->result();
    }

    public function get_applicant_by_appstatus($jobID,$appStatus,$dq,$district)
    {
        $this->db->select("
        a.*,
        j.jvStatus,j.jobID,j.job_type,j.jobTitle,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.resProvince, staff.resProvince) AS province,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        COALESCE(app.age, staff.age) AS age,
        COALESCE(app.Sex, staff.Sex) AS sex,
        COALESCE(app.MaritalStatus, staff.MaritalStatus) AS ms,
        COALESCE(app.empEmail, staff.empEmail) AS email,
        COALESCE(app.contactNo, staff.contactNo) AS cn,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ");
    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    $this->db->join('hris_jobvacancy j', 'a.jobID = j.jobID', 'left');
    $this->db->where('a.jobID', $jobID);
    $this->db->where('a.appStatus', $appStatus);
    $this->db->where('a.district', $district);
    $this->db->where('a.dq', $dq);

    $query = $this->db->get();
    return $query->result();
    }

    public function get_staff_by_plantilla_group()
{
    $this->db->select('hris_plantilla.pGroup, hris_staff.*');
    $this->db->from('hris_plantilla');
    $this->db->join('hris_staff', 'hris_staff.itemNo = hris_plantilla.itemNo', 'left'); 
    $this->db->order_by('hris_plantilla.pGroup');
    
    $query = $this->db->get();
    return $query->result();
}

public function get_grouped_staff() {
        $this->db->select('hris_plantilla.*, hris_staff.*, hris_plantilla.itemNo as ren');
        $this->db->from('hris_plantilla');
        $this->db->join('hris_staff', 'hris_plantilla.itemNo = hris_staff.itemNo', 'left');
        $this->db->order_by('hris_plantilla.pGroup', 'ASC');
        return $this->db->get()->result();
}

public function get_grouped_staff_limit($pgroup) {
        $this->db->select('hris_plantilla.*, hris_staff.*, hris_plantilla.itemNo as ren');
        $this->db->from('hris_plantilla');
        $this->db->join('hris_staff', 'hris_plantilla.itemNo = hris_staff.itemNo', 'left'); 
        $this->db->where('hris_plantilla.pGroup', $pgroup);
        $this->db->order_by('hris_plantilla.pGroup', 'ASC');
        return $this->db->get()->result();
}

public function smea_count_by_pillar($con1, $con2, $con3, $con4, $q) {
    $this->db->select('a.*, b.*');
    $this->db->from('sgod_aip as a');
    $this->db->join('sgod_sop as b', 'a.id = b.aip_id');
    $this->db->where('a.school_id', $con1);
    $this->db->where('a.fy', $con2);
    $this->db->where('a.b_code', $con3);
    $this->db->where('a.pillar', $con4);

    $this->db->where('b.'.$q.' !=', 0);
    $this->db->where('b.'.$q.' !=', '');


    return $this->db->get(); 
}


function safe_unlink($file)
{
    return (is_file($file) && file_exists($file)) ? unlink($file) : false;
}

    // public function promotion_list($jobID)
    // {
    //     $this->db->select("
    //     a.*,
    //     r.*,
    //     staff.stat AS ren,
    //     COALESCE(app.record_no, staff.IDNumber) AS code,
    //     COALESCE(app.id, staff.IDNumber) AS id,
    //     COALESCE(app.empEmail, staff.IDNumber) AS renren,
    //     COALESCE(app.contactNo, staff.contactNo) AS contactNo,
    //     COALESCE(app.FirstName, staff.FirstName) AS FirstName,
    //     COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
    //     COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
    //     COALESCE(app.LastName, staff.LastName) AS LastName,
    //     COALESCE(app.perProvince, staff.perProvince) AS province,
    //     COALESCE(app.jhss, staff.jhss) AS jhss,
    //     COALESCE(app.shss, staff.shss) AS shss,
    //     COALESCE(app.resCity, staff.resCity) AS resCity,
    //     COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
    //     CASE
    //         WHEN app.record_no IS NOT NULL THEN 'ma'
    //         WHEN staff.IDNumber IS NOT NULL THEN 'ma_staff'
    //         ELSE 'unknown'
    //     END AS st
    // ");
    // $this->db->from('hris_applications a');
    // $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    // $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');
    // $this->db->join('hris_rating_promotion r', 'a.appID = r.appID', 'left');
    // $this->db->where('a.jobID', $jobID);
    // $this->db->where('a.dq', 1);
    // $this->db->order_by('LastName', 'ASC');

    // $query = $this->db->get();
    // return $query->result();
    // }


    public function promotion_list($jobID)
    {
        // Subquery: latest rating per appID
        $ratingSub = "
            (
                SELECT rp.*
                FROM hris_rating_promotion rp
                JOIN (
                    SELECT appID, MAX(id) AS max_id
                    FROM hris_rating_promotion
                    GROUP BY appID
                ) x ON x.appID = rp.appID AND x.max_id = rp.id
            ) r
        ";

        $this->db->select("
            a.*,
            r.*,
            staff.stat AS ren,
            COALESCE(app.record_no, staff.IDNumber) AS code,
            COALESCE(app.id, staff.IDNumber) AS id,
            COALESCE(app.empEmail, staff.IDNumber) AS renren,
            COALESCE(app.contactNo, staff.contactNo) AS contactNo,
            COALESCE(app.FirstName, staff.FirstName) AS FirstName,
            COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
            COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
            COALESCE(app.LastName, staff.LastName) AS LastName,
            COALESCE(app.perProvince, staff.perProvince) AS province,
            COALESCE(app.jhss, staff.jhss) AS jhss,
            COALESCE(app.shss, staff.shss) AS shss,
            COALESCE(app.resCity, staff.resCity) AS resCity,
            COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
            CASE
                WHEN app.record_no IS NOT NULL THEN 'ma'
                WHEN staff.IDNumber    IS NOT NULL THEN 'ma_staff'
                ELSE 'unknown'
            END AS st
        ", false);

        $this->db->from('hris_applications a');
        $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
        $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');

        $this->db->join($ratingSub, 'r.appID = a.appID', 'left', false);

        $this->db->where('a.jobID', $jobID);
        $this->db->where('a.dq', 1);


        $this->db->order_by('LastName', 'ASC');

        $query = $this->db->get();
        return $query->result();
}

public function qualified_applicant_list_nt($jobID)
{
    $ratingSub = "
        (
            SELECT rp.*
            FROM hris_rating_none rp
            JOIN (
                SELECT appID, MAX(id) AS max_id
                FROM hris_rating_none
                GROUP BY appID
            ) x ON x.appID = rp.appID AND x.max_id = rp.id
        ) r
    ";

    $this->db->select("
        a.*,
        r.*,
        staff.stat AS ren,
        COALESCE(app.record_no, staff.IDNumber) AS code,
        COALESCE(app.id, staff.IDNumber) AS id,
        COALESCE(app.empEmail, staff.IDNumber) AS renren,
        COALESCE(app.contactNo, staff.contactNo) AS contactNo,
        COALESCE(app.FirstName, staff.FirstName) AS FirstName,
        COALESCE(app.MiddleName, staff.MiddleName) AS MiddleName,
        COALESCE(app.NameExtn, staff.NameExtn) AS NameExtn,
        COALESCE(app.LastName, staff.LastName) AS LastName,
        COALESCE(app.perProvince, staff.perProvince) AS province,
        COALESCE(app.jhss, staff.jhss) AS jhss,
        COALESCE(app.shss, staff.shss) AS shss,
        COALESCE(app.resCity, staff.resCity) AS resCity,
        COALESCE(app.resBarangay, staff.resBarangay) AS brgy,
        COALESCE(app.bd, staff.bd) AS bachelor,
        COALESCE(app.Department, staff.Department) AS Department,
        COALESCE(app.schoolID, staff.schoolID) AS schoolID,
        CASE
            WHEN app.record_no IS NOT NULL THEN 'ma'
            WHEN staff.IDNumber    IS NOT NULL THEN 'ma_staff'
            ELSE 'unknown'
        END AS st
    ", false);

    $this->db->from('hris_applications a');
    $this->db->join('hris_applicant app', 'a.empEmail = app.empEmail', 'left');
    $this->db->join('hris_staff staff', 'app.record_no IS NULL AND a.empEmail = staff.IDNumber', 'left');

    $this->db->join($ratingSub, 'r.appID = a.appID', 'left', false);

    $this->db->where('a.jobID', $jobID);
    $this->db->where('a.dq', 1);


    $this->db->order_by('LastName', 'ASC');

    $query = $this->db->get();
    return $query->result();
}

public function find_by_idnumber_union($id)
{
    $sql = "
        SELECT 
            'hris_applicant' AS source,
            a.record_no      AS code,
            a.FirstName      AS FirstName,
            a.LastName       AS LastName,
            a.empEmail       AS Email,
            a.empMobile      AS contact,
            a.bd             AS edu,
            a.MaritalStatus  AS ms,
            a.ethnicity      AS ethnicity,
            a.religion       AS religion,
            a.disability     AS disability,
            a.sgNo                  AS sg,
            a.lastAppointmentDate   AS lad,
            a.jobTitle              AS jobTitle,
            a.payCat              AS payCat
        FROM hris_applicant a
        WHERE a.record_no = ?

        UNION ALL

        SELECT 
            'hris_staff'     AS source,
            s.IDNumber       AS code,
            s.FirstName      AS FirstName,
            s.LastName       AS LastName,
            s.empEmail       AS Email,
            s.empMobile      AS contact,
            s.bd             AS edu,
            s.MaritalStatus  AS ms,
            s.ethnicity       AS ethnicity,
            s.religion       AS religion,
            s.disability       AS disability,
            s.sgNo       AS sg,
            s.lastAppointmentDate       AS lad,
            s.jobTitle              AS jobTitle,
            s.payCat              AS payCat
        FROM hris_staff s
        WHERE s.IDNumber = ?
    ";

    $query = $this->db->query($sql, [$id, $id]);
    return $query->row(); // first match, or null if none
}


    public function get_applications_with_rating_status($stat = 0)
{
    $this->db->select("
        ai.application_id,
        ai.stat AS inquiry_stat,
        ai.id,
        ai.idate,
        ai.job_id,
        jv.jobTitle,
        jv.department,
        jv.position,
        ha.pre_school,
        ha.applicant_id,

        -- FIX 1: avoid MAX() on text (causes collation issues)
        MIN(COALESCE(rn.record_no, rp.record_no, ar.record_no)) AS record_no,
        MIN(COALESCE(rn.eval_id1,  rp.eval_id1,  ar.eval_id1 )) AS eval_id1,

        MIN(u.id) AS user_id,

        -- FIX 1: avoid MAX(CONCAT(...)) too
        MIN(TRIM(CONCAT(
            IFNULL(u.fname,''), ' ',
            IFNULL(u.mname,''), ' ',
            IFNULL(u.lname,'')
        ))) AS rater_fullname,

        CASE
            WHEN rn.appID IS NOT NULL THEN 'No Rating'
            WHEN rp.appID IS NOT NULL THEN 'Promotion Rating'
            WHEN ar.appID IS NOT NULL THEN 'Application Rating'
            ELSE 'Not Rated'
        END AS rating_status,

        -- resolve where record_no belongs
        CASE
            WHEN ap.record_no IS NOT NULL THEN 'Applicant'
            WHEN st.IDNumber IS NOT NULL THEN 'Staff'
            ELSE NULL
        END AS rater_type,

        -- resolved fullname
        TRIM(COALESCE(
            CONCAT(ap.FirstName,' ', IFNULL(ap.MiddleName,''),' ', ap.LastName),
            CONCAT(st.FirstName,' ', IFNULL(st.MiddleName,''),' ', st.LastName)
        )) AS fullname
    ", false);

    $this->db->from('hris_application_inquiry ai');
    $this->db->join('hris_jobvacancy jv', 'jv.jobID = ai.job_id', 'inner');

    $this->db->join('hris_applications ha', 'ha.appID = ai.application_id', 'left');

    // rating tables
    $this->db->join('hris_rating_none rn', 'rn.appID = ai.application_id', 'left');
    $this->db->join('hris_rating_promotion rp', 'rp.appID = ai.application_id', 'left');
    $this->db->join('hris_applications_rating ar', 'ar.appID = ai.application_id', 'left');

    // FIX 2: collation mix for "=" (latin1 vs utf8mb4) -> CONVERT both sides to utf8mb4
    $this->db->join(
        'hris_applicant ap',
        "CONVERT(ap.record_no USING utf8mb4) = CONVERT(COALESCE(rn.record_no, rp.record_no, ar.record_no) USING utf8mb4)",
        'left',
        false
    );

    $this->db->join(
        'hris_staff st',
        "CONVERT(st.IDNumber USING utf8mb4) = CONVERT(COALESCE(rn.record_no, rp.record_no, ar.record_no) USING utf8mb4)",
        'left',
        false
    );

    // users join (ids are numeric so no collation issue here)
    $this->db->join(
        'users u',
        'u.id = COALESCE(rn.eval_id1, rp.eval_id1, ar.eval_id1)',
        'left',
        false
    );

    // only open vacancies
     $this->db->where('jv.jvStatus', 'open');

     // exclude empty application id
     $this->db->where('ai.application_id IS NOT NULL', null, false);
     $this->db->where('ai.application_id <>', '');

     // filter inquiry status: 0 = pending, 1 = finalized
     if ($stat !== null) {
         $this->db->where('ai.stat', (int)$stat);
     }

     // group to avoid duplicates due to joins
    $this->db->group_by([
        'ai.application_id',
        'ai.stat',
        'ai.id',
        'ai.idate',
        'ai.job_id',
        'jv.jobTitle',
        'jv.department',
        'jv.position',
        'ha.pre_school',
        'ha.applicant_id',

        // keep these because they affect rater_type/fullname resolution
        'ap.record_no',
        'st.IDNumber',
        'ap.FirstName','ap.MiddleName','ap.LastName',
        'st.FirstName','st.MiddleName','st.LastName'
    ]);

    $this->db->order_by('ai.application_id', 'DESC');

    return $this->db->get()->result();
}

public function find_record_no_identity($record_no)
{
    $record_no = trim((string)$record_no);
    if ($record_no === '') return null;

    $sql = "
        (SELECT 
            s.IDNumber,
            TRIM(CONCAT(
                IFNULL(s.FirstName,''), ' ',
                IFNULL(s.MiddleName,''), ' ',
                IFNULL(s.LastName,'')
            )) AS fullname,
            'Staff' AS source
        FROM hris_staff s
        WHERE s.IDNumber = ?)

        UNION ALL

        (SELECT 
            a.record_no,
            TRIM(CONCAT(
                IFNULL(a.FirstName,''), ' ',
                IFNULL(a.MiddleName,''), ' ',
                IFNULL(a.LastName,'')
            )) AS fullname,
            'Applicant' AS source
        FROM hris_applicant a
        WHERE a.record_no = ?)

        LIMIT 1
    ";

    $q = $this->db->query($sql, [$record_no, $record_no]);
    $row = $q->row();

    if (!$row) return null;

    // Clean spacing (avoid double spaces)
    $row->fullname = trim(preg_replace('/\s+/', ' ', (string)$row->fullname));

    return $row;
}

public function get_agenda_indicators()
{
    return $this->db
        ->select("id, `group`, indicator")
        ->from("agenda")
        ->order_by("`id`", "ASC")
        ->order_by("indicator", "ASC")
        ->get()
        ->result();
}



}
