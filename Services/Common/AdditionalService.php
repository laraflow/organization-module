<?php

namespace Modules\Organization\Services\Common;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Common\Additional;
use Modules\Organization\Repositories\Eloquent\Common\AdditionalRepository;
use Throwable;

/**
 * @class AdditionalService
 * @package Modules\Organization\Services\Common
 */
class AdditionalService extends Service
{
/**
     * @var AdditionalRepository
     */
    private $additionalRepository;

    /**
     * AdditionalService constructor.
     * @param AdditionalRepository $additionalRepository
     */
    public function __construct(AdditionalRepository $additionalRepository)
    {
        $this->additionalRepository = $additionalRepository;
        $this->additionalRepository->itemsPerPage = 10;
    }

    /**
     * Get All Additional models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllAdditionals(array $filters = [], array $eagerRelations = [])
    {
        return $this->additionalRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Additional Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function additionalPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->additionalRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Additional Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getAdditionalById($id, bool $purge = false)
    {
        return $this->additionalRepository->show($id, $purge);
    }

    /**
     * Save Additional Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeAdditional(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newAdditional = $this->additionalRepository->create($inputs);
            if ($newAdditional instanceof Additional) {
                DB::commit();
                return ['status' => true, 'message' => __('New Additional Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Additional Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->additionalRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Additional Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateAdditional(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $additional = $this->additionalRepository->show($id);
            if ($additional instanceof Additional) {
                if ($this->additionalRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Additional Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Additional Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Additional Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->additionalRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Additional Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyAdditional($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->additionalRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Additional is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Additional is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->additionalRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Additional Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreAdditional($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->additionalRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Additional is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Additional is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->additionalRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return AdditionalExport
     * @throws Exception
     */
    public function exportAdditional(array $filters = []): AdditionalExport
    {
        return (new AdditionalExport($this->additionalRepository->getWith($filters)));
    }
}
