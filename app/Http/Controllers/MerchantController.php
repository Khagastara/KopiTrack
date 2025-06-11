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

            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data merchant berhasil diambil',
                    'data' => $merchants
                ], 200);
            }

            return view('admin.merchants.index', compact('merchants'));
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengambil data merchant',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal mengambil data merchant: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.merchants.create');
    }

    public function show($id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Merchant tidak ditemukan'
                    ], 404);
                }

                return redirect()->route('merchants.index')->with('error', 'Merchant tidak ditemukan');
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Detail merchant berhasil diambil',
                    'data' => $merchant
                ], 200);
            }

            return view('admin.merchants.show', compact('merchant'));
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengambil detail merchant',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal mengambil detail merchant: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                return redirect()->route('merchants.index')->with('error', 'Merchant tidak ditemukan');
            }

            return view('admin.merchants.edit', compact('merchant'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengambil data merchant: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:accounts,username',
            'email' => 'required|string|email|max:255|unique:accounts,email',
            'password' => 'required|string|min:6|confirmed',
            'merchant_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:merchants,phone_number'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
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

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Akun merchant berhasil dibuat',
                    'data' => $merchant
                ], 201);
            }

            return redirect()->route('merchants.index')->with('success', 'Akun merchant berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat akun merchant',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal membuat akun merchant: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $merchant = Merchant::with('Account')->find($id);

            if (!$merchant) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Merchant tidak ditemukan'
                    ], 404);
                }

                return redirect()->route('merchants.index')->with('error', 'Merchant tidak ditemukan');
            }

            $rules = [
                'username' => 'sometimes|string|max:255|unique:accounts,username,' . $merchant->Account->id,
                'email' => 'sometimes|string|email|max:255|unique:accounts,email,' . $merchant->Account->id,
                'merchant_name' => 'sometimes|string|max:255',
                'phone_number' => 'sometimes|string|max:20|unique:merchants,phone_number,' . $merchant->id
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'string|min:6|confirmed';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $accountData = [];
            if ($request->filled('username')) {
                $accountData['username'] = $request->username;
            }
            if ($request->filled('email')) {
                $accountData['email'] = $request->email;
            }
            if ($request->filled('password')) {
                $accountData['password'] = Hash::make($request->password);
            }

            if (!empty($accountData)) {
                $merchant->Account->update($accountData);
            }

            $merchantData = [];
            if ($request->filled('merchant_name')) {
                $merchantData['merchant_name'] = $request->merchant_name;
            }
            if ($request->filled('phone_number')) {
                $merchantData['phone_number'] = $request->phone_number;
            }

            if (!empty($merchantData)) {
                $merchant->update($merchantData);
            }

            $merchant->refresh();
            $merchant->load('Account');

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data merchant berhasil diperbarui',
                    'data' => $merchant
                ], 200);
            }

            return redirect()->route('merchants.index')->with('success', 'Data merchant berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui data merchant',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui data merchant: ' . $e->getMessage())->withInput();
        }
    }
}
