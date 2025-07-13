<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePemesananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu_id'       => 'sometimes|exists:menus,id',
            'jumlah'        => 'sometimes|integer|min:1',
            'status_pesanan' => 'sometimes|string|in:diterima,diproses,dikirim,selesai',
            'pengiriman'    => 'sometimes|string',
            'metode_pembayaran'    => 'sometimes|string',
            'status_bayar'  => 'sometimes|in:sudah,belum',
        ];
    }
}
