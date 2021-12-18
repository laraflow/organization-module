<?php

namespace Modules\Organization\Services\Common;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Common\Profile;
use Modules\Organization\Repositories\Eloquent\Common\ProfileRepository;
use Throwable;

/**
 * @class ProfileService
 * @package Modules\Organization\Services\Common
 */
class ProfileService extends Service
{
/**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * ProfileService constructor.
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->profileRepository->itemsPerPage = 10;
    }

    /**
     * Get All Profile models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllProfiles(array $filters = [], array $eagerRelations = [])
    {
        return $this->profileRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Profile Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function profilePaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->profileRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Profile Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getProfileById($id, bool $purge = false)
    {
        return $this->profileRepository->show($id, $purge);
    }

    /**
     * Save Profile Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeProfile(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newProfile = $this->profileRepository->create($inputs);
            if ($newProfile instanceof Profile) {
                DB::commit();
                return ['status' => true, 'message' => __('New Profile Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Profile Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->profileRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Profile Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateProfile(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $profile = $this->profileRepository->show($id);
            if ($profile instanceof Profile) {
                if ($this->profileRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Profile Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Profile Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Profile Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->profileRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Profile Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyProfile($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->profileRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Profile is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Profile is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->profileRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Profile Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreProfile($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->profileRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Profile is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Profile is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->profileRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return ProfileExport
     * @throws Exception
     */
    public function exportProfile(array $filters = []): ProfileExport
    {
        return (new ProfileExport($this->profileRepository->getWith($filters)));
    }
}
