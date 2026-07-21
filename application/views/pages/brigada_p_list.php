<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <?php if (!empty($can_manage_partners)): ?>
                                <a href="<?= base_url(); ?>Brigada/settings_partners" class="btn btn-success btn-sm">Add Partner</a>
                            <?php endif; ?>
                        </div>
                        <h4 class="page-title"><?= htmlspecialchars($title ?? 'Partners', ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('danger'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Contact Person</th>
                                            <th>Contact</th>
                                            <th>Type</th>
                                            <th>Specific Type</th>
                                            <?php if (!empty($can_manage_partners)): ?>
                                                <th class="text-center">Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ((array) $data as $row): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= htmlspecialchars($row->name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($row->address ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($row->contact_person ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($row->contact ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($row->general_type ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($row->specific_type ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <?php if (!empty($can_manage_partners)): ?>
                                                    <td class="text-center">
                                                        <a href="<?= base_url(); ?>Brigada/partners_update/<?= (int) $row->id; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <a href="<?= base_url(); ?>Brigada/partners_delete/<?= (int) $row->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this partner?');">Delete</a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($data)): ?>
                                            <tr>
                                                <td colspan="<?= !empty($can_manage_partners) ? 8 : 7; ?>" class="text-center">No partners found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
