<?php

namespace Modules\Organization\Services\Common;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Abstracts\Service\Service;
use Modules\Core\Supports\Constant;
use Modules\Organization\Models\Common\Market;
use Modules\Organization\Repositories\Eloquent\Common\MarketRepository;
use Throwable;

/**
 * @class MarketService
 * @package Modules\Organization\Services\Common
 */
class MarketService extends Service
{
/**
     * @var MarketRepository
     */
    private $marketRepository;

    /**
     * MarketService constructor.
     * @param MarketRepository $marketRepository
     */
    public function __construct(MarketRepository $marketRepository)
    {
        $this->marketRepository = $marketRepository;
        $this->marketRepository->itemsPerPage = 10;
    }

    /**
     * Get All Market models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllMarkets(array $filters = [], array $eagerRelations = [])
    {
        return $this->marketRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Market Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function marketPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->marketRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Market Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getMarketById($id, bool $purge = false)
    {
        return $this->marketRepository->show($id, $purge);
    }

    /**
     * Save Market Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeMarket(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newMarket = $this->marketRepository->create($inputs);
            if ($newMarket instanceof Market) {
                DB::commit();
                return ['status' => true, 'message' => __('New Market Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Market Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->marketRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Market Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateMarket(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $market = $this->marketRepository->show($id);
            if ($market instanceof Market) {
                if ($this->marketRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Market Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Market Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Market Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->marketRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Market Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyMarket($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->marketRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Market is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Market is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->marketRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Market Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreMarket($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->marketRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Market is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Market is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->marketRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return MarketExport
     * @throws Exception
     */
    public function exportMarket(array $filters = []): MarketExport
    {
        return (new MarketExport($this->marketRepository->getWith($filters)));
    }
}
