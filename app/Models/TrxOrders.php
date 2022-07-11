<?php
namespace App\Models;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class TrxOrders extends Model
{
    public static $tableName = "trx_orders";

    public static $connection = "mysql";

    
	private $id;
	private $customers_id;
	private $periode;
	private $packages_id;
	private $vouchers_code;
	private $payment_method;
	private $tgl_mulai;
	private $protein;
	private $protein_alternative;
	private $carbo;
	private $carbo_alternative;
	private $day_off;
	private $address_book_id;
	private $drivers_id;
	private $status_berlangganan;
	private $status_payment;
	private $payment_date;
	private $created_at;
	private $updated_at;
	private $address;
	private $detail_address;
	private $latitude;
	private $longitude;
	private $nama_penerima;
	private $no_penerima;
	private $no_order;
	private $price;
	private $total;
	private $address_name_second;
	private $address_second;
	private $detail_address_second;
	private $latitude_second;
	private $longitude_second;
	private $nama_penerima_second;
	private $no_penerima_second;
	private $drivers_id_second;
	private $must_end;
	private $day_for;
	private $day_for_altf;
	private $catatan;
	private $catatan_altf;
	private $photo_pengiriman;
	private $nama_penerima_pesanan;
	private $catatan_driver;
	private $is_paused;
	private $date_payment;
	private $no_rek;
	private $nama_rek;
	private $photo_payment;
	private $type_apps;

    /**
     * @return mixed
     */
    public function getTypeApps()
    {
        return $this->type_apps;
    }

    /**
     * @param mixed $type_apps
     */
    public function setTypeApps($type_apps): void
    {
        $this->type_apps = $type_apps;
    }
    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public static function findAllByCustomersId($value) {
		return static::simpleQuery()->where('customers_id',$value)->get();
	}

	/**
	* @return Customers
	*/
	public function getCustomersId() {
		return Customers::findById($this->customers_id);
	}

	public function setCustomersId($customers_id) {
		$this->customers_id = $customers_id;
	}

	public static function findAllByPeriode($value) {
		return static::simpleQuery()->where('periode',$value)->get();
	}

	public static function findByPeriode($value) {
		return static::findBy('periode',$value);
	}

	public function getPeriode() {
		return $this->periode;
	}

	public function setPeriode($periode) {
		$this->periode = $periode;
	}

	public static function findAllByPackagesId($value) {
		return static::simpleQuery()->where('packages_id',$value)->get();
	}

	/**
	* @return Packages
	*/
	public function getPackagesId() {
		return Packages::findById($this->packages_id);
	}

	public function setPackagesId($packages_id) {
		$this->packages_id = $packages_id;
	}

	public static function findAllByVouchersCode($value) {
		return static::simpleQuery()->where('vouchers_code',$value)->get();
	}

	public static function findByVouchersCode($value) {
		return static::findBy('vouchers_code',$value);
	}

	public function getVouchersCode() {
		return $this->vouchers_code;
	}

	public function setVouchersCode($vouchers_code) {
		$this->vouchers_code = $vouchers_code;
	}

	public static function findAllByPaymentMethod($value) {
		return static::simpleQuery()->where('payment_method',$value)->get();
	}

	public static function findByPaymentMethod($value) {
		return static::findBy('payment_method',$value);
	}

	public function getPaymentMethod() {
		return $this->payment_method;
	}

	public function setPaymentMethod($payment_method) {
		$this->payment_method = $payment_method;
	}

	public static function findAllByTglMulai($value) {
		return static::simpleQuery()->where('tgl_mulai',$value)->get();
	}

	public static function findByTglMulai($value) {
		return static::findBy('tgl_mulai',$value);
	}

	public function getTglMulai() {
		return $this->tgl_mulai;
	}

	public function setTglMulai($tgl_mulai) {
		$this->tgl_mulai = $tgl_mulai;
	}

	public static function findAllByProtein($value) {
		return static::simpleQuery()->where('protein',$value)->get();
	}

	public static function findByProtein($value) {
		return static::findBy('protein',$value);
	}

	public function getProtein() {
		return $this->protein;
	}

	public function setProtein($protein) {
		$this->protein = $protein;
	}

	public static function findAllByProteinAlternative($value) {
		return static::simpleQuery()->where('protein_alternative',$value)->get();
	}

	public static function findByProteinAlternative($value) {
		return static::findBy('protein_alternative',$value);
	}

	public function getProteinAlternative() {
		return $this->protein_alternative;
	}

	public function setProteinAlternative($protein_alternative) {
		$this->protein_alternative = $protein_alternative;
	}

	public static function findAllByCarbo($value) {
		return static::simpleQuery()->where('carbo',$value)->get();
	}

	public static function findByCarbo($value) {
		return static::findBy('carbo',$value);
	}

	public function getCarbo() {
		return $this->carbo;
	}

	public function setCarbo($carbo) {
		$this->carbo = $carbo;
	}

	public static function findAllByCarboAlternative($value) {
		return static::simpleQuery()->where('carbo_alternative',$value)->get();
	}

	public static function findByCarboAlternative($value) {
		return static::findBy('carbo_alternative',$value);
	}

	public function getCarboAlternative() {
		return $this->carbo_alternative;
	}

	public function setCarboAlternative($carbo_alternative) {
		$this->carbo_alternative = $carbo_alternative;
	}

	public static function findAllByDayOff($value) {
		return static::simpleQuery()->where('day_off',$value)->get();
	}

	public static function findByDayOff($value) {
		return static::findBy('day_off',$value);
	}

	public function getDayOff() {
		return $this->day_off;
	}

	public function setDayOff($day_off) {
		$this->day_off = $day_off;
	}

	public static function findAllByAddressBookId($value) {
		return static::simpleQuery()->where('address_book_id',$value)->get();
	}

	/**
	* @return AddressBook
	*/
	public function getAddressBookId() {
		return $this->address_book_id;
	}

	public function setAddressBookId($address_book_id) {
		$this->address_book_id = $address_book_id;
	}

	public static function findAllByDriversId($value) {
		return static::simpleQuery()->where('drivers_id',$value)->get();
	}

	/**
	* @return Drivers
	*/
	public function getDriversId() {
		return Drivers::findById($this->drivers_id);
	}

	public function setDriversId($drivers_id) {
		$this->drivers_id = $drivers_id;
	}

	public static function findAllByStatusBerlangganan($value) {
		return static::simpleQuery()->where('status_berlangganan',$value)->get();
	}

	public static function findByStatusBerlangganan($value) {
		return static::findBy('status_berlangganan',$value);
	}

	public function getStatusBerlangganan() {
		return $this->status_berlangganan;
	}

	public function setStatusBerlangganan($status_berlangganan) {
		$this->status_berlangganan = $status_berlangganan;
	}

	public static function findAllByStatusPayment($value) {
		return static::simpleQuery()->where('status_payment',$value)->get();
	}

	public static function findByStatusPayment($value) {
		return static::findBy('status_payment',$value);
	}

	public function getStatusPayment() {
		return $this->status_payment;
	}

	public function setStatusPayment($status_payment) {
		$this->status_payment = $status_payment;
	}

	public static function findAllByPaymentDate($value) {
		return static::simpleQuery()->where('payment_date',$value)->get();
	}

	public static function findByPaymentDate($value) {
		return static::findBy('payment_date',$value);
	}

	public function getPaymentDate() {
		return $this->payment_date;
	}

	public function setPaymentDate($payment_date) {
		$this->payment_date = $payment_date;
	}

	public static function findAllByCreatedAt($value) {
		return static::simpleQuery()->where('created_at',$value)->get();
	}

	public static function findByCreatedAt($value) {
		return static::findBy('created_at',$value);
	}

	public function getCreatedAt() {
		return $this->created_at;
	}

	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}

	public static function findAllByUpdatedAt($value) {
		return static::simpleQuery()->where('updated_at',$value)->get();
	}

	public static function findByUpdatedAt($value) {
		return static::findBy('updated_at',$value);
	}

	public function getUpdatedAt() {
		return $this->updated_at;
	}

	public function setUpdatedAt($updated_at) {
		$this->updated_at = $updated_at;
	}

	public static function findAllByAddress($value) {
		return static::simpleQuery()->where('address',$value)->get();
	}

	public static function findByAddress($value) {
		return static::findBy('address',$value);
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public static function findAllByDetailAddress($value) {
		return static::simpleQuery()->where('detail_address',$value)->get();
	}

	public static function findByDetailAddress($value) {
		return static::findBy('detail_address',$value);
	}

	public function getDetailAddress() {
		return $this->detail_address;
	}

	public function setDetailAddress($detail_address) {
		$this->detail_address = $detail_address;
	}

	public static function findAllByLatitude($value) {
		return static::simpleQuery()->where('latitude',$value)->get();
	}

	public static function findByLatitude($value) {
		return static::findBy('latitude',$value);
	}

	public function getLatitude() {
		return $this->latitude;
	}

	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	public static function findAllByLongitude($value) {
		return static::simpleQuery()->where('longitude',$value)->get();
	}

	public static function findByLongitude($value) {
		return static::findBy('longitude',$value);
	}

	public function getLongitude() {
		return $this->longitude;
	}

	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

	public static function findAllByNamaPenerima($value) {
		return static::simpleQuery()->where('nama_penerima',$value)->get();
	}

	public static function findByNamaPenerima($value) {
		return static::findBy('nama_penerima',$value);
	}

	public function getNamaPenerima() {
		return $this->nama_penerima;
	}

	public function setNamaPenerima($nama_penerima) {
		$this->nama_penerima = $nama_penerima;
	}

	public static function findAllByNoPenerima($value) {
		return static::simpleQuery()->where('no_penerima',$value)->get();
	}

	public static function findByNoPenerima($value) {
		return static::findBy('no_penerima',$value);
	}

	public function getNoPenerima() {
		return $this->no_penerima;
	}

	public function setNoPenerima($no_penerima) {
		$this->no_penerima = $no_penerima;
	}

	public static function findAllByNoOrder($value) {
		return static::simpleQuery()->where('no_order',$value)->get();
	}

	public static function findByNoOrder($value) {
		return static::findBy('no_order',$value);
	}

	public function getNoOrder() {
		return $this->no_order;
	}

	public function setNoOrder($no_order) {
		$this->no_order = $no_order;
	}

	public static function findAllByPrice($value) {
		return static::simpleQuery()->where('price',$value)->get();
	}

	public static function findByPrice($value) {
		return static::findBy('price',$value);
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice($price) {
		$this->price = $price;
	}

	public static function findAllByTotal($value) {
		return static::simpleQuery()->where('total',$value)->get();
	}

	public static function findByTotal($value) {
		return static::findBy('total',$value);
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal($total) {
		$this->total = $total;
	}

	public static function findAllByAddressNameSecond($value) {
		return static::simpleQuery()->where('address_name_second',$value)->get();
	}

	public static function findByAddressNameSecond($value) {
		return static::findBy('address_name_second',$value);
	}

	public function getAddressNameSecond() {
		return $this->address_name_second;
	}

	public function setAddressNameSecond($address_name_second) {
		$this->address_name_second = $address_name_second;
	}

	public static function findAllByAddressSecond($value) {
		return static::simpleQuery()->where('address_second',$value)->get();
	}

	public static function findByAddressSecond($value) {
		return static::findBy('address_second',$value);
	}

	public function getAddressSecond() {
		return $this->address_second;
	}

	public function setAddressSecond($address_second) {
		$this->address_second = $address_second;
	}

	public static function findAllByDetailAddressSecond($value) {
		return static::simpleQuery()->where('detail_address_second',$value)->get();
	}

	public static function findByDetailAddressSecond($value) {
		return static::findBy('detail_address_second',$value);
	}

	public function getDetailAddressSecond() {
		return $this->detail_address_second;
	}

	public function setDetailAddressSecond($detail_address_second) {
		$this->detail_address_second = $detail_address_second;
	}

	public static function findAllByLatitudeSecond($value) {
		return static::simpleQuery()->where('latitude_second',$value)->get();
	}

	public static function findByLatitudeSecond($value) {
		return static::findBy('latitude_second',$value);
	}

	public function getLatitudeSecond() {
		return $this->latitude_second;
	}

	public function setLatitudeSecond($latitude_second) {
		$this->latitude_second = $latitude_second;
	}

	public static function findAllByLongitudeSecond($value) {
		return static::simpleQuery()->where('longitude_second',$value)->get();
	}

	public static function findByLongitudeSecond($value) {
		return static::findBy('longitude_second',$value);
	}

	public function getLongitudeSecond() {
		return $this->longitude_second;
	}

	public function setLongitudeSecond($longitude_second) {
		$this->longitude_second = $longitude_second;
	}

	public static function findAllByNamaPenerimaSecond($value) {
		return static::simpleQuery()->where('nama_penerima_second',$value)->get();
	}

	public static function findByNamaPenerimaSecond($value) {
		return static::findBy('nama_penerima_second',$value);
	}

	public function getNamaPenerimaSecond() {
		return $this->nama_penerima_second;
	}

	public function setNamaPenerimaSecond($nama_penerima_second) {
		$this->nama_penerima_second = $nama_penerima_second;
	}

	public static function findAllByNoPenerimaSecond($value) {
		return static::simpleQuery()->where('no_penerima_second',$value)->get();
	}

	public static function findByNoPenerimaSecond($value) {
		return static::findBy('no_penerima_second',$value);
	}

	public function getNoPenerimaSecond() {
		return $this->no_penerima_second;
	}

	public function setNoPenerimaSecond($no_penerima_second) {
		$this->no_penerima_second = $no_penerima_second;
	}

	public static function findAllByDriversIdSecond($value) {
		return static::simpleQuery()->where('drivers_id_second',$value)->get();
	}

	public static function findByDriversIdSecond($value) {
		return static::findBy('drivers_id_second',$value);
	}

	public function getDriversIdSecond() {
		return $this->drivers_id_second;
	}

	public function setDriversIdSecond($drivers_id_second) {
		$this->drivers_id_second = $drivers_id_second;
	}

	public static function findAllByMustEnd($value) {
		return static::simpleQuery()->where('must_end',$value)->get();
	}

	public static function findByMustEnd($value) {
		return static::findBy('must_end',$value);
	}

	public function getMustEnd() {
		return $this->must_end;
	}

	public function setMustEnd($must_end) {
		$this->must_end = $must_end;
	}

	public static function findAllByDayFor($value) {
		return static::simpleQuery()->where('day_for',$value)->get();
	}

	public static function findByDayFor($value) {
		return static::findBy('day_for',$value);
	}

	public function getDayFor() {
		return $this->day_for;
	}

	public function setDayFor($day_for) {
		$this->day_for = $day_for;
	}

	public static function findAllByDayForAltf($value) {
		return static::simpleQuery()->where('day_for_altf',$value)->get();
	}

	public static function findByDayForAltf($value) {
		return static::findBy('day_for_altf',$value);
	}

	public function getDayForAltf() {
		return $this->day_for_altf;
	}

	public function setDayForAltf($day_for_altf) {
		$this->day_for_altf = $day_for_altf;
	}

	public static function findAllByCatatan($value) {
		return static::simpleQuery()->where('catatan',$value)->get();
	}

	public static function findByCatatan($value) {
		return static::findBy('catatan',$value);
	}

	public function getCatatan() {
		return $this->catatan;
	}

	public function setCatatan($catatan) {
		$this->catatan = $catatan;
	}

	public static function findAllByCatatanAltf($value) {
		return static::simpleQuery()->where('catatan_altf',$value)->get();
	}

	public static function findByCatatanAltf($value) {
		return static::findBy('catatan_altf',$value);
	}

	public function getCatatanAltf() {
		return $this->catatan_altf;
	}

	public function setCatatanAltf($catatan_altf) {
		$this->catatan_altf = $catatan_altf;
	}

	public static function findAllByPhotoPengiriman($value) {
		return static::simpleQuery()->where('photo_pengiriman',$value)->get();
	}

	public static function findByPhotoPengiriman($value) {
		return static::findBy('photo_pengiriman',$value);
	}

	public function getPhotoPengiriman() {
		return $this->photo_pengiriman;
	}

	public function setPhotoPengiriman($photo_pengiriman) {
		$this->photo_pengiriman = $photo_pengiriman;
	}

	public static function findAllByNamaPenerimaPesanan($value) {
		return static::simpleQuery()->where('nama_penerima_pesanan',$value)->get();
	}

	public static function findByNamaPenerimaPesanan($value) {
		return static::findBy('nama_penerima_pesanan',$value);
	}

	public function getNamaPenerimaPesanan() {
		return $this->nama_penerima_pesanan;
	}

	public function setNamaPenerimaPesanan($nama_penerima_pesanan) {
		$this->nama_penerima_pesanan = $nama_penerima_pesanan;
	}

	public static function findAllByCatatanDriver($value) {
		return static::simpleQuery()->where('catatan_driver',$value)->get();
	}

	public static function findByCatatanDriver($value) {
		return static::findBy('catatan_driver',$value);
	}

	public function getCatatanDriver() {
		return $this->catatan_driver;
	}

	public function setCatatanDriver($catatan_driver) {
		$this->catatan_driver = $catatan_driver;
	}

	public static function findAllByIsPaused($value) {
		return static::simpleQuery()->where('is_paused',$value)->get();
	}

	public static function findByIsPaused($value) {
		return static::findBy('is_paused',$value);
	}

	public function getIsPaused() {
		return $this->is_paused;
	}

	public function setIsPaused($is_paused) {
		$this->is_paused = $is_paused;
	}

	public static function findAllByDatePayment($value) {
		return static::simpleQuery()->where('date_payment',$value)->get();
	}

	public static function findByDatePayment($value) {
		return static::findBy('date_payment',$value);
	}

	public function getDatePayment() {
		return $this->date_payment;
	}

	public function setDatePayment($date_payment) {
		$this->date_payment = $date_payment;
	}

	public static function findAllByNoRek($value) {
		return static::simpleQuery()->where('no_rek',$value)->get();
	}

	public static function findByNoRek($value) {
		return static::findBy('no_rek',$value);
	}

	public function getNoRek() {
		return $this->no_rek;
	}

	public function setNoRek($no_rek) {
		$this->no_rek = $no_rek;
	}

	public static function findAllByNamaRek($value) {
		return static::simpleQuery()->where('nama_rek',$value)->get();
	}

	public static function findByNamaRek($value) {
		return static::findBy('nama_rek',$value);
	}

	public function getNamaRek() {
		return $this->nama_rek;
	}

	public function setNamaRek($nama_rek) {
		$this->nama_rek = $nama_rek;
	}

	public static function findAllByPhotoPayment($value) {
		return static::simpleQuery()->where('photo_payment',$value)->get();
	}

	public static function findByPhotoPayment($value) {
		return static::findBy('photo_payment',$value);
	}

	public function getPhotoPayment() {
		return $this->photo_payment;
	}

	public function setPhotoPayment($photo_payment) {
		$this->photo_payment = $photo_payment;
	}


}