-- MariaDB dump 10.19  Distrib 10.4.25-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: toko_sepatu
-- ------------------------------------------------------
-- Server version	10.4.25-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Casual & Sneakers','casual-sneakers'),(2,'Sports & Performance','sports-performance'),(3,'Formal & Dress Shoes','formal-dress-shoes');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `kurir` varchar(20) NOT NULL,
  `layanan` varchar(50) NOT NULL,
  `ongkir` decimal(10,2) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid','Cancelled') NOT NULL DEFAULT 'Pending',
  `tgl_pesan` datetime NOT NULL,
  `tgl_bayar` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pesanan`),
  UNIQUE KEY `invoice_no` (`invoice_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (1,'INV-20260527-001','Aditya Pratama','aditya@gmail.com','08123456789','Jl. Merak No. 10','DI Yogyakarta','Sleman','JNE','REG (Reguler)',17000.00,1617000.00,'Paid','2026-05-26 14:20:00','2026-05-26 14:35:00'),(2,'INV-20260527-002','Lestari Indah','lestari@yahoo.com','08987654321','Jl. Diponegoro No. 25','Jawa Tengah','Surakarta (Solo)','J&T','EZ',15000.00,815000.00,'Paid','2026-05-27 09:10:00','2026-05-27 09:25:00');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan_detail`
--

DROP TABLE IF EXISTS `pesanan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanan_detail` (
  `id_pesanan_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_detail` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `warna` varchar(30) NOT NULL,
  PRIMARY KEY (`id_pesanan_detail`),
  KEY `id_pesanan` (`id_pesanan`),
  KEY `id_detail` (`id_detail`),
  CONSTRAINT `pesanan_detail_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_detail_ibfk_2` FOREIGN KEY (`id_detail`) REFERENCES `produk_detail` (`id_detail`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan_detail`
--

LOCK TABLES `pesanan_detail` WRITE;
/*!40000 ALTER TABLE `pesanan_detail` DISABLE KEYS */;
INSERT INTO `pesanan_detail` VALUES (1,1,1,2,1,1500000.00,'41','Triple White'),(2,2,10,46,1,800000.00,'41','Tan Brown');
/*!40000 ALTER TABLE `pesanan_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (1,'Nike Air Force 1 \'07','Desain ikonik, sol empuk, dan ketahanan luar biasa. Sepatu basket klasik legendaris yang kini didesain ulang untuk kenyamanan gaya sehari-hari Anda.',1500000.00,1,'nike_af1.jpg'),(2,'New Balance MR530','Menampilkan siluet retro tahun 90-an yang sangat sejuk (breathable) berkat panel mesh berkualitas tinggi. Dilengkapi dengan sol ABZORB untuk kenyamanan melangkah sepanjang hari.',1200000.00,1,'nb_530.jpg'),(3,'Adidas Samba Classic','Dengan siluet ramping, bahan suede lembut dipadukan dengan kulit premium, serta sol karet mentah (gum sole) yang khas. Gaya vintage kasual terbaik.',1400000.00,1,'adidas_samba.jpg'),(4,'Compass Gazelle Low','Brand sneakers lokal kebanggaan Indonesia dengan jahitan samping kokoh dan material kanvas tebal 12 oz. Nyaman, vintage, dan sangat terjangkau.',400000.00,1,'compass_gazelle.jpg'),(5,'Nike Pegasus 41','Didesain khusus untuk Road Running dengan teknologi ReactX foam terbaru yang memberikan pantulan ekstra responsif dan hemat energi, dikemas dengan bobot yang sangat ringan.',2100000.00,2,'nike_pegasus41.jpg'),(6,'Asics Gel-Kayano 30','Pilihan utama para pelari maraton dengan dukungan teknologi Gel legendaris di bagian tumit untuk meredam benturan lutut secara optimal, menjaga stabilitas langkah jarak jauh.',2400000.00,2,'asics_kayano.jpg'),(7,'TR2 Trail Shoes','Dirancang tangguh untuk lari lintas alam (Trail Running). Dilengkapi sol dengan grip kasar berpola lug tajam anti-slip, menjamin cengkeraman maksimal di medan lumpur, tanah basah, maupun berbatu.',800000.00,2,'tr2_trail.jpg'),(8,'Mills Triton Voltas','Sepatu sepak bola & futsal berperforma tinggi dengan upper kulit sintetik lentur yang mencengkeram bola dengan presisi tinggi, serta formasi stud paku sol yang sangat kokoh untuk akselerasi tajam.',350000.00,2,'mills_triton.jpg'),(9,'Fransisca Renaldy Pablo 03','Model Loafers tanpa tali klasik yang praktis namun formal. Terbuat dari kulit asli pilihan (Genuine Leather) dengan finishing semi-gloss yang anggun.',350000.00,3,'fransisca_renaldy.jpg'),(10,'Portee Goods Oxford','Sepatu formal model Oxford resmi bertali. Menggunakan kulit sapi jenis pull-up premium dan dikerjakan dengan metode sol Goodyear Welted yang legendaris, awet dan dapat diganti solnya.',800000.00,3,'portee_oxford.jpg'),(11,'Brodo Base Chelsea','Sepatu boots model Chelsea yang elegan dengan karet elastis di bagian samping. Menggunakan kulit sintetis premium pilihan yang lembut dan sol karet Brodo yang kokoh dan anti-slip.',400000.00,3,'brodo_chelsea.jpg');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk_detail`
--

DROP TABLE IF EXISTS `produk_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produk_detail` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `warna` varchar(30) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_detail`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `produk_detail_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk_detail`
--

LOCK TABLES `produk_detail` WRITE;
/*!40000 ALTER TABLE `produk_detail` DISABLE KEYS */;
INSERT INTO `produk_detail` VALUES (1,1,'40','Triple White',15),(2,1,'41','Triple White',19),(3,1,'42','Triple White',18),(4,1,'41','Triple Black',10),(5,1,'42','Triple Black',12),(6,2,'40','White Silver',8),(7,2,'41','White Silver',15),(8,2,'42','White Silver',10),(9,2,'41','Steel Grey',8),(10,2,'42','Steel Grey',12),(11,3,'40','Core Black White',10),(12,3,'41','Core Black White',12),(13,3,'42','Core Black White',15),(14,3,'40','Cloud White Black',14),(15,3,'41','Cloud White Black',18),(16,4,'39','Black White',25),(17,4,'40','Black White',30),(18,4,'41','Black White',20),(19,4,'40','Navy White',15),(20,4,'41','Navy White',18),(21,5,'41','Volt Green',8),(22,5,'42','Volt Green',10),(23,5,'41','White Orange',12),(24,5,'42','White Orange',14),(25,6,'41','Black Electric Blue',10),(26,6,'42','Black Electric Blue',15),(27,6,'43','Black Electric Blue',8),(28,6,'42','French Blue',10),(29,6,'43','French Blue',12),(30,7,'40','Grey Lime',12),(31,7,'41','Grey Lime',15),(32,7,'42','Grey Lime',10),(33,7,'41','Orange Black',8),(34,7,'42','Orange Black',12),(35,8,'39','Cyan Blue',15),(36,8,'40','Cyan Blue',20),(37,8,'41','Cyan Blue',18),(38,8,'40','White Red',12),(39,8,'41','White Red',15),(40,9,'40','Black Leather',10),(41,9,'41','Black Leather',12),(42,9,'42','Black Leather',15),(43,9,'40','Dark Brown Leather',8),(44,9,'41','Dark Brown Leather',14),(45,10,'40','Tan Brown',10),(46,10,'41','Tan Brown',11),(47,10,'42','Tan Brown',15),(48,10,'43','Tan Brown',8),(49,11,'40','Brown Suede',15),(50,11,'41','Brown Suede',20),(51,11,'42','Brown Suede',18),(52,11,'41','Black Premium',12),(53,11,'42','Black Premium',10);
/*!40000 ALTER TABLE `produk_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-27 13:02:57
