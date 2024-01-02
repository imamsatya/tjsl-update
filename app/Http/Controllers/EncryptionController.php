<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EncryptionController extends Controller
{
    //
    public function encryptData(Request $request)
    {
        // Retrieve the data to be encrypted from the request
        $dataToEncrypt = $request->input('data');

        // Use a secret key for encryption (make sure to keep it secure)
        $secretKey = "your_secret_key";

        // Encrypt the data using Laravel's Crypt facade
        $encryptedData = Crypt::encryptString($dataToEncrypt);

        // Return the encrypted value
        return response()->json(['encryptedValue' => $encryptedData]);
    }

    // public function decryptData(Request $request)
    // {
    //     // Retrieve the data to be decrypted from the request
    //     $encryptedData = $request->input('data');

    //     // Use a secret key for decryption (should be the same key used for encryption)
    //     $secretKey = "your_secret_key";

    //     try {
    //         // Decrypt the data using Laravel's Crypt facade
    //         $decryptedData = Crypt::decryptString($encryptedData);

    //         // Return the decrypted value
    //         return response()->json(['decryptedValue' => $decryptedData]);
    //     } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
    //         // Handle decryption errors (e.g., if the key is incorrect)
    //         return response()->json(['error' => 'Decryption failed.']);
    //     }
    // }

    

}
