<?php

namespace Modules\Organization\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Organization;
use Modules\Organization\Repositories\Eloquent\OrganizationRepository;
use Throwable;

/**
 * @class OrganizationService
 * @package Modules\Organization\Services
 */
class OrganizationService extends Service
{
/**
     * @var OrganizationRepository
     */
    private $organizationRepository;

    /**
     * OrganizationService constructor.
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationRepository->itemsPerPage = 10;
    }

    /**
     * Get All Organization models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllOrganizations(array $filters = [], array $eagerRelations = [])
    {
        return $this->organizationRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Organization Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function organizationPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->organizationRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Organization Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getOrganizationById($id, bool $purge = false)
    {
        return $this->organizationRepository->show($id, $purge);
    }

    /**
     * Save Organization Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeOrganization(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newOrganization = $this->organizationRepository->create($inputs);
            if ($newOrganization instanceof Organization) {
                DB::commit();
                return ['status' => true, 'message' => __('New Organization Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Organization Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->organizationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Organization Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateOrganization(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $organization = $this->organizationRepository->show($id);
            if ($organization instanceof Organization) {
                if ($this->organizationRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Organization Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Organization Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Organization Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->organizationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Organization Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyOrganization($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->organizationRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Organization is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Organization is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->organizationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Organization Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreOrganization($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->organizationRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Organization is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Organization is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->organizationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return OrganizationExport
     * @throws Exception
     */
    public function exportOrganization(array $filters = []): OrganizationExport
    {
        return (new OrganizationExport($this->organizationRepository->getWith($filters)));
    }
}
