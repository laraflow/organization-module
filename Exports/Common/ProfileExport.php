<?php

namespace Modules\Organization\Exports\Common;

use Box\Spout\Common\Exception\InvalidArgumentException;
use Modules\Core\Abstracts\Export\FastExcelExport;
use Modules\Core\Models\Setting\Permission;
use Modules\Organization\Models\Common\Profile;

/**
 * @class ProfileExport
 * @package Modules\Organization\Exports\Common
 */
class ProfileExport extends FastExcelExport
{
    /**
     * ProfileExport constructor.
     *
     * @param null $data
     * @throws InvalidArgumentException
     */
    public function __construct($data = null)
    {
        parent::__construct();

        $this->data($data);
    }

    /**
     * @param Profile $row
     * @return array
     */
    public function map($row): array
    {
        $this->formatRow = [
            '#' => $row->id,
            'Name' => $row->name,
            'Remarks' => $row->remarks,
            'Enabled' => ucfirst($row->enabled),
            'Created' => $row->created_at->format(config('app.datetime')),
            'Updated' => $row->updated_at->format(config('app.datetime'))
        ];

        $this->getSupperAdminColumns($row);

        return $this->formatRow;
    }
}

