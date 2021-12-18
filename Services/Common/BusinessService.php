<?php

namespace Modules\Organization\Services\Common;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Common\Business;
use Modules\Organization\Repositories\Eloquent\Common\BusinessRepository;
use Throwable;

/**
 * @class BusinessService
 * @package Modules\Organization\Services\Common
 */
class BusinessService extends Service
{
/**
     * @var BusinessRepository
     */
    private $businessRepository;

    /**
     * BusinessService constructor.
     * @param BusinessRepository $businessRepository
     */
    public function __construct(BusinessRepository $businessRepository)
    {
        $this->businessRepository = $businessRepository;
        $this->businessRepository->itemsPerPage = 10;
    }

    /**
     * Get All Business models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllBusinesss(array $filters = [], array $eagerRelations = [])
    {
        return $this->businessRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Business Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function businessPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->businessRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Business Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getBusinessById($id, bool $purge = false)
    {
        return $this->businessRepository->show($id, $purge);
    }

    /**
     * Save Business Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeBusiness(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newBusiness = $this->businessRepository->create($inputs);
            if ($newBusiness instanceof Business) {
                DB::commit();
                return ['status' => true, 'message' => __('New Business Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Business Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->businessRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Business Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateBusiness(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $business = $this->businessRepository->show($id);
            if ($business instanceof Business) {
                if ($this->businessRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Business Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Business Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Business Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->businessRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Business Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyBusiness($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->businessRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Business is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Business is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->businessRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Business Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreBusiness($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->businessRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Business is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Business is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->businessRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return BusinessExport
     * @throws Exception
     */
    public function exportBusiness(array $filters = []): BusinessExport
    {
        return (new BusinessExport($this->businessRepository->getWith($filters)));
    }
}
