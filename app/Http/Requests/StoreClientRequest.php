<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'internet_package_id' => 'integer',

            'name' => 'required|min:3|max:191',
            'ip_address' => 'required|max:191',
            'phone_number' => 'required|min:3|max:191',
            'house_image' => 'required|image',
            'address' => 'required|max:191',
            'nik' => 'nullable|string|size:16',
            'email' => 'required|email|unique:clients,email,' . $this->route('klien'),
            'password' => 'nullable|min:6'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'internet_package_id.integer' => 'Kolom paket internet wajib diisi!',

            'name.required' => 'Kolom nama klien wajib diisi!',
            'name.min' => 'Kolom nama klien minimal :min karakter!',
            'name.max' => 'Kolom nama klien maksimal :max karakter!',

            'ip_address.required' => 'Kolom alamat IP wajib diisi!',
            'ip_address.max' => 'Kolom alamat IP maksimal :max karakter!',

            'phone_number.required' => 'Kolom nomor handphone wajib diisi!',
            'phone_number.min' => 'Kolom nomor handphone minimal :min karakter!',
            'phone_number.max' => 'Kolom nomor handphone maksimal :max karakter!',

            'house_image.required' => 'Kolom foto rumah wajib diisi!',
            'house_image.image' => 'Kolom foto rumah harus gambar!',

            'address.required' => 'Kolom alamat wajib diisi!',
            'address.max' => 'Kolom alamat maksimal :max karakter!',

            'nik.string' => 'Kolom NIK harus berupa karakter!',
            'nik.size' => 'Kolom NIK harus 16 digit!'
        ];
    }
}
