<?php
// BAB 2 CLASS
echo "<h2>BAB 2 CLASS</h2>";
class Mobil {
    public $merk;
    public $warna;

    public function jalan() {
        return "Mobil Jalan";
    }
}

$mobil1 = new Mobil();
$mobil1->merk = "Toyota";
$mobil1->warna = "Biru";

echo ($mobil1->jalan() . " dengan warna " . $mobil1->warna . "<br>");

// BAB 3 CONSTRUCTOR
echo "<h2>BAB 3 CONSTRUCTOR</h2>";
class Siswa {
    public $nama;

    public function __construct($nama) {
        $this->nama = $nama;
    }

    // BAB 3 DESTRUCTOR
    public function __destruct() {
        echo "OBJEK TELAH DI HANCURKAN";
    }
}

$siswa1 = new Siswa("Budi");
echo $siswa1->nama;

// BAB 4 PEWARISAN / INHERITANCE
echo "<h2>BAB 4 PEWARISAN / INHERITANCE</h2>";
class Manusia {
    public $nama;
    public function sapa() {
        return "Halo, Saya $this->nama";
    }
}

class Guru extends Manusia {
    public function mengajar() {
        return "$this->nama sedang mengajar";
    }
}

$guru1 = new Guru();
$guru1->nama = "Pak Budi";
echo $guru1->mengajar();

// BAB 5 ENKAPSULASI
echo "<h2>BAB 5 ENKAPSULASI</h2>";
class Bank {
    private $saldo = 10000;
    public function lihatSaldo() {
        return $this->saldo;
    }
}

$rekening = new Bank();
echo "Saldo Dari Bank : " . $rekening->lihatSaldo();

// BAB 6 Polimorfism
echo "<h2>BAB 6 Polimorfism</h2>";

class Hewan {
    public function suara() {
        return "Hewan bersuara";
    }
}

class Kucing extends Hewan {
    public function suara() {
        return "Meong";
    }
}

class Anjing extends Hewan {
    public function suara() {
        return "Auuu";
    }
}

function bunyikanSuara(Hewan $hewan) {
    echo $hewan->suara();
}

$kucing = new Kucing();
bunyikanSuara($kucing);
