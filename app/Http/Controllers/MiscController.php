<?php

namespace App\Http\Controllers;

use App\Events\UserLogs;
use App\Models\Albums;
use App\Models\Media;
use App\Models\ShopSettings;
use App\Services\MiscService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class MiscController extends Controller
{
    private $miscService;  // Private property to store the MiscService instance

    public function __construct(MiscService $miscService)
    {
        $this->miscService = $miscService;  // Inject the MiscService instance into the controller
    }

    /**
     * Export data from a specified module.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
            ]);
        }

        try {
            // Log the user update event
            event(new UserLogs($request->user()->userid, 'Export', "Exported data from module with ID $request->modID", 'POST /api/export', '201'));

            // Return a JSON response indicating successful export
            return response()->json([
                "ok" => true,
                "msg" => "Records exported successfully!",
            ]);
        } catch (\Throwable $e) {
            // Catch and handle any exceptions that occurred
            return response()->json([
                "ok" => false,
                "msg" => "Request failed. An internal error occured",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }

    public function shopSettings(Request $request)
    {
        // Check permission using the MiscService checkPermission method
        if (!$this->miscService->checkPermission($request)) {
            // Return a JSON response indicating permission failure
            return response()->json([
                "ok" => false,
                "msg" => "Request failed! You do not have permission to perform this operation!",
            ]);
        }

        $validator = Validator::make($request->all(), [
            'id' => ['required','exists:shop_settings,id'],
            'tax_rate' => ['nullable', 'numeric'],
            'tax_amount' => ['nullable', 'numeric'],
            'tax_type' => ['nullable', 'in:RATE,AMOUNT'],
            'apply_tax' => ['nullable', 'in:YES,NO'],
            'vat_rate' => ['nullable', 'numeric'],
            'vat_amount' => ['nullable', 'numeric'],
            'vat_type' => ['nullable', 'in:RATE,AMOUNT'],
            'apply_vat' => ['nullable', 'in:YES,NO'],
            'discount_rate' => ['nullable', 'numeric'],
            'discount_amount' => ['nullable', 'numeric'],
            'discount_type' => ['nullable', 'in:RATE,AMOUNT'],
            'apply_discount' => ['nullable', 'in:YES,NO'],
            'shop_status' => ['nullable', 'in:ONLINE,OFFLINE'],
        ], [
            "id.required" => "No ID supplied",
            "id.exists" => "Record not found!",
            "tax_rate.numeric" => "Tax rate must be numeric!",
            "tax_amount.numeric" => "Tax amount must be numeric!",
            "tax_type.in" => "Selected tax type must be either RATE or AMOUNT!",
            "apply_vat.in" => "Selected tax type application must be either YES or NO!",
            "vat_rate.numeric" => "VAT rate must be numeric!",
            "vat_amount.numeric" => "VAT amount must be numeric!",
            "vat_type.in" => "Selected VAT type must be either RATE or AMOUNT!",
            "vat_type.in" => "Selected VAT type application must be either YES or NO!",
            "discount_rate.numeric" => "Discount rate must be numeric!",
            "discount_amount.numeric" => "Discount amount must be numeric!",
            "discount_type.in" => "Selected discount type must be either RATE or AMOUNT!",
            "apply_discount.in" => "Selected discount type application must be either YES or NO!",
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation error message
            return response()->json([
                "ok" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        try {
            $settings = ShopSettings::find($request->id);

            $settings->update([
                'tax_rate' => $request->tax_rate,
                'tax_amount' => $request->tax_amount,
                'tax_type' => $request->tax_type,
                'apply_tax' => $request->apply_tax,
                'vat_rate' => $request->vat_rate,
                'vat_amount' => $request->vat_amount,
                'vat_type' => $request->vat_type,
                'apply_vat' => $request->apply_vat,
                'discount_rate' => $request->discount_rate,
                'discount_amount' => $request->discount_amount,
                'discount_type' => $request->discount_type,
                'apply_discount' => $request->apply_discount,
                'shop_start_time' => $request->shop_start_time,
                'shop_end_time' => $request->shop_end_time,
                'shop_status' => $request->shop_status,
                'allowed_users_offline' => $request->allowed_users_offline,
                'modifyuser' => $request->user()->userid
            ]);

            // Trigger user logs event
            event(new UserLogs($request->user()->userid, "Shop Settings", "Shop settings updated successfully", 'POST /api/shop-settings', '201'));

            // Return a JSON response indicating successful record addition
            return response()->json([
                "ok" => true,
                "msg" => "Shop settings updated successfully",
            ]);
        } catch (\Throwable $e) {
            // Catch and handle any exceptions that occurred during the transaction
            return response()->json([
                "ok" => false,
                "msg" => "An error occurred while adding record, please contact admin",
                "error" => [
                    "msg" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                ]
            ]);
        }
    }
}
