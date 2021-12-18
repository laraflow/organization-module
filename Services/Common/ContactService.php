<?php

namespace Modules\Organization\Services\Common;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Common\Contact;
use Modules\Organization\Repositories\Eloquent\Common\ContactRepository;
use Throwable;

/**
 * @class ContactService
 * @package Modules\Organization\Services\Common
 */
class ContactService extends Service
{
/**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * ContactService constructor.
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->contactRepository->itemsPerPage = 10;
    }

    /**
     * Get All Contact models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllContacts(array $filters = [], array $eagerRelations = [])
    {
        return $this->contactRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Contact Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function contactPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->contactRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Contact Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getContactById($id, bool $purge = false)
    {
        return $this->contactRepository->show($id, $purge);
    }

    /**
     * Save Contact Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeContact(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newContact = $this->contactRepository->create($inputs);
            if ($newContact instanceof Contact) {
                DB::commit();
                return ['status' => true, 'message' => __('New Contact Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Contact Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->contactRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Contact Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateContact(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $contact = $this->contactRepository->show($id);
            if ($contact instanceof Contact) {
                if ($this->contactRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Contact Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Contact Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Contact Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->contactRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Contact Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyContact($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->contactRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Contact is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Contact is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->contactRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Contact Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreContact($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->contactRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Contact is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Contact is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->contactRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return ContactExport
     * @throws Exception
     */
    public function exportContact(array $filters = []): ContactExport
    {
        return (new ContactExport($this->contactRepository->getWith($filters)));
    }
}
