<?php

namespace Laraditz\UserSecurity\Services;

use FurqanSiddiqui\BIP39\BIP39;
use Laraditz\UserSecurity\Models\UserSecurity;

class MnemonicService
{
    /**
     * Generate mnemonic object.
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function generate()
    {
        $word_count = config('usersecurity.mnemonic.word_count');

        $mnemonic = BIP39::Generate($word_count);
        $hashed_entropy = self::hash($mnemonic->entropy);

        // ensure the mnemonic is unique
        while (UserSecurity::where('mnemonic_entropy', $hashed_entropy)->count()) {
            $mnemonic = BIP39::Generate($word_count);
            $hashed_entropy = self::hash($mnemonic->entropy);
        }

        return $mnemonic;
    }

    /**
     * Use mnemonic codes to find entropy.
     *
     * @param array $words
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function words(array $words)
    {
        return BIP39::Words($words);
    }

    /**
     * Generate Mnemonic using specified Entropy.
     *
     * @param string @entropy
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function entropy($entropy)
    {
        return BIP39::Entropy($entropy);
    }

    /**
     * Generate a hash for mnemonic entropy.
     *
     * @param string $value
     *
     * @return string
     */
    public function hash(string $value): string
    {
        $hash_key = config('usersecurity.key');
        $hash_algo = config('usersecurity.algo');

        return hash($hash_algo, $value . $hash_key);
    }

    /**
     * Find user by words.
     *
     * @param array $words
     *
     * @return string
     */
    public function userByWords(array $words)
    {
        try {
            $mnemonic = self::words($words);
            $hashed_entropy = self::hash($mnemonic->entropy);

            if ($userSecurity = UserSecurity::where('mnemonic_entropy', $hashed_entropy)->first()) {
                return  $userSecurity->model_type::find($userSecurity->model_id);
            }
        } catch (Exception $e) {
            throw new UnexpectedValueException('Invalid words.');
        }
    }

    /**
     * Find user by words.
     *
     * @param array $words
     *
     * @return string
     */
    public function verifyByWords(array $words, $entropy)
    {
        try {
            $mnemonic = self::words($words);
            $hashed_entropy = self::hash($mnemonic->entropy);

            if ($hashed_entropy === $entropy) {
                return true;
            }
        } catch (Exception $e) {
            throw new UnexpectedValueException('Words does not match with record.');
        }

        return false;
    }
}
