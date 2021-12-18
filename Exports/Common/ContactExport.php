<?php

namespace Modules\Organization\Exports\Common;

use Box\Spout\Common\Exception\InvalidArgumentException;
use Modules\Core\Abstracts\Export\FastExcelExport;
use Modules\Core\Models\Setting\Permission;
use Modules\Organization\Models\Common\Contact;

/**
 * @class ContactExport
 * @package Modules\Organization\Exports\Common
 */
class ContactExport extends FastExcelExport
{
    /**
     * ContactExport constructor.
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
     * @param Contact $row
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

