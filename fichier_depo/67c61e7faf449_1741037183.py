from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
import os

def pad(data):
    """Ajoute un bourrage au message pour qu'il soit un multiple de 16 octets"""
    padding_length = 16 - (len(data) % 16)
    return data + bytes([padding_length] * padding_length)

def unpad(data):
    """Supprime le bourrage ajouté après le déchiffrement"""
    padding_length = data[-1]
    return data[:-padding_length]

def encrypt_file(input_file, output_file, key_file):
    """Chiffre un fichier avec AES-128 en mode CBC"""
    key = get_random_bytes(16)  # Clé de 16 octets (AES-128)
    iv = get_random_bytes(16)   # IV de 16 octets

    # Sauvegarde la clé et l'IV pour le déchiffrement
    with open(key_file, "wb") as kf:
        kf.write(key + iv)

    # Lecture du fichier d'entrée
    with open(input_file, "rb") as f:
        plaintext = f.read()

    # Ajout du bourrage
    padded_data = pad(plaintext)

    # Chiffrement
    cipher = AES.new(key, AES.MODE_CBC, iv)
    ciphertext = cipher.encrypt(padded_data)

    # Sauvegarde du fichier chiffré
    with open(output_file, "wb") as cf:
        cf.write(ciphertext)

    print(f"Fichier '{input_file}' chiffré et sauvegardé sous '{output_file}'.")
    print(f"Clé et IV sauvegardés dans '{key_file}'.")

def decrypt_file(encrypted_file, decrypted_file, key_file):
    """Déchiffre un fichier chiffré avec AES-128 en mode CBC"""
    # Récupération de la clé et de l'IV
    with open(key_file, "rb") as kf:
        key_iv = kf.read()
        key, iv = key_iv[:16], key_iv[16:]

    # Lecture du fichier chiffré
    with open(encrypted_file, "rb") as cf:
        ciphertext = cf.read()

    # Déchiffrement
    cipher = AES.new(key, AES.MODE_CBC, iv)
    padded_plaintext = cipher.decrypt(ciphertext)

    # Suppression du bourrage
    plaintext = unpad(padded_plaintext)

    # Sauvegarde du fichier déchiffré
    with open(decrypted_file, "wb") as df:
        df.write(plaintext)

    print(f"Fichier '{encrypted_file}' déchiffré et sauvegardé sous '{decrypted_file}'.")

# Exemple d'utilisation :
# encrypt_file("input.txt", "encrypted.bin", "key.bin")
# decrypt_file("encrypted.bin", "decrypted.txt", "key.bin")
