<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    public function index()
    {
        try {
            $merchants = Merchant::with('Account')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data merchant berhasil diambil',
                'data' => $merchants
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data merchant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merchant tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Detail merchant berhasil diambil',
                'data' => $merchant
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail merchant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:accounts,username',
            'email' => 'required|string|email|max:255|unique:accounts,email',
            'password' => 'required|string|min:6',
            'merchant_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:merchants,phone_number'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $account = Account::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $merchant = Merchant::create([
                'merchant_name' => $request->merchant_name,
                'phone_number' => $request->phone_number,
                'id_account' => $account->id
            ]);

            $merchant->load('Account');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Akun merchant berhasil dibuat',
                'data' => $merchant
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat akun merchant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merchant tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|max:255|unique:accounts,username,' . $merchant->Account->id,
                'email' => 'sometimes|string|email|max:255|unique:accounts,email,' . $merchant->Account->id,
                'password' => 'sometimes|string|min:6',
                'merchant_name' => 'sometimes|string|max:255',
                'phone_number' => 'sometimes|string|max:20|unique:merchants,phone_number,' . $merchant->id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $accountData = [];
            if ($request->has('username')) {
                $accountData['username'] = $request->username;
            }
            if ($request->has('email')) {
                $accountData['email'] = $request->email;
            }
            if ($request->has('password')) {
                $accountData['password'] = Hash::make($request->password);
            }

            if (!empty($accountData)) {
                $merchant->Account->update($accountData);
            }

            $merchantData = [];
            if ($request->has('merchant_name')) {
                $merchantData['merchant_name'] = $request->merchant_name;
            }
            if ($request->has('phone_number')) {
                $merchantData['phone_number'] = $request->phone_number;
            }

            if (!empty($merchantData)) {
                $merchant->update($merchantData);
            }

            $merchant->refresh();
            $merchant->load('Account');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data merchant berhasil diperbarui',
                'data' => $merchant
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data merchant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merchant tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();

            $merchant->delete();

            $merchant->Account->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Merchant berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus merchant',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
