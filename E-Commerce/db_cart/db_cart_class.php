<?php
/************************************************************************
db_cart Class ver 1.11 -
This universal shopping cart script is powered by MySQL and works with external customer and product related tables.

Copyright (c) 2005, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

*************************************************************************/
require($_SERVER['DOCUMENT_ROOT']."/extranet/include/db_cart/db_config.php");
if (!session_id()) session_start();
error_reporting(E_ALL);
class db_cart {
	
	var $error;
	var $customer;
	var $curr_product;
	
	var $ship_id;
	var $ship_name;
	var $ship_address;
	var $ship_pc;
	var $ship_city;
	var $ship_country; 
	var $ship_msg;
	
	var $order_array = array();
	
	
	// constructor ...
	function db_cart($customer_no = 0) {
		$conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_NAME, $conn);
		$this->error = "&nbsp;";
		if (!isset($_SESSION['order_id'])) {
			$this->get_order($customer_no);
			$this->remove_old_orders(true); // use false if everyting older 1 day should be removed
		}
	}
	// get all messages from here (add your own language here)
	function messages($number) {
		$msg = array();
		// general error messages
		$msg[1] = "Unknown database error, please try it again.";
		$msg[2] = "Unknown application error, please contact the system administrator.";
		$msg[1] = "Unknown database error, but the order is send by e-mail.";
		// messages related to cart activity
		$msg[11] = "Added the product to your cart.";
		$msg[12] = "Updated the order row in your cart.";
		$msg[13] = "Can't add/update the product, please try it again.";
		$msg[14] = "Add some products to the cart...";
		$msg[15] = "Removed the order row from your cart.";
		// checkout related messages
		$msg[21] = "Shipment address is successfully modified.";
		$msg[22] = "Your order is processed and a copy is send to you by e-mail.";		
		// messages used inside the mail
		$msg[51] = "Order posted via ".$_SERVER['HTTP_HOST']." on ".date(DATE_FORMAT);
		$msg[52] = "Hello,\r\n\r\nyou ordered the following products:\r\n\r\n";
		$msg[53] = "\r\nThe total amount from your order is: ".$this->format_value($this->show_total_value(), false);
		$msg[53] .= (INCL_VAT) ? " (incl. VAT)" : "";
		$msg[54] = "\r\nThe VAT amount for your order is: ".$this->format_value($this->create_total_VAT(), false)."\r\n\r\n";
		$msg[55] = "\r\n\r\n_______________________________________\r\nkind regards\r\n".SITE_MASTER."\r\n\r\n";
		// if you change the format next then change the values in the mail method, too.
		$msg[56] = sprintf("\t%-10s  %-30s  %-20s  %-12s\r\n", "Quantity", "Description", "Art. no.", "Single price"); 
		$msg[57] = "Shipment to / ordered by:\r\n";
		$msg[57] .= $this->ship_name."\r\n".$this->ship_address."\r\n".$this->ship_pc." ".$this->ship_city."\r\n".$this->ship_country;
		$msg[58] = ($this->ship_msg != "") ? "\r\n\r\nMessage:\r\n".$this->ship_msg."\r\n" : ""; 
		return $msg[$number];
	}
	// mail message to send critical error's to the admin
	function send_admin_mail() {
	    // mail to admin (comes with the next version)
	}
	// function to handle old orders
	// a method to clean up old orders, $remove_only_zeros is an option to handle records for records with a customer number
	function remove_old_orders($remove_only_zeros = true) {
		if (RECOVER_ORDER) {
			$sql = sprintf("DELETE FROM %s WHERE open = 'y' AND  order_date < (NOW() - %d)", ORDERS, VALID_UNTIL * 86400);
		} else {
			$sql = sprintf("DELETE FROM %s WHERE open = 'y' AND customer = %d AND order_date < (NOW() - %d)", ORDERS, VALID_UNTIL * 86400);
		}
		$sql .= ($remove_only_zeros) ? " AND customer = 0" : "";
		mysql_query($sql);
	}
	// get an existing order for a customer, or insert a new one id none exist
	function get_order($customer) {
		$sql_check = sprintf("SELECT id FROM %s WHERE customer = %d AND open = 'y'", ORDERS, $customer);
		if ($res_check = mysql_query($sql_check)) {
			if (mysql_num_rows($res_check) > 0 && $customer != 0) {
				$_SESSION['order_id'] = mysql_result($res_check, 0, "id");
			} else {
				$sql_new = sprintf("INSERT INTO %s (customer, order_date) VALUES (%d, NOW())", ORDERS, $customer);
				if (mysql_query($sql_new)) {
					$_SESSION['order_id'] = mysql_insert_id();
				} else {
					$this->error = $this->messages(1);
				}
			}
		} else {
			$this->error = $this->messages(1);
		}
	}
	// this method will chek if a order row for this product already exist
	function check_existing_row($product) {
		$sql = sprintf("SELECT id FROM %s WHERE order_id = %d AND product_id = '%s'", ORDER_ROWS, $_SESSION['order_id'], $product);
		if ($result = mysql_query($sql)) {
			if (mysql_num_rows($result) == 1) {
				$this->curr_product = mysql_result($result, 0, "id");
				return "old";
			} else {
				return "new";
			}
		} else {
			return "error";
		}	
	}
	// insert a not existing row to the current order
	function insert_row($prod_id, $prod_name, $quantity, $price, $vat_amount = VAT_VALUE) {
		$sql = sprintf("INSERT INTO %s (order_id, product_id, product_name, price, vat_perc, quantity) VALUES (%d, '%s', '%s', %f, %f, %d)", ORDER_ROWS, $_SESSION['order_id'], $prod_id, $prod_name, $price, $vat_amount, $quantity);
		if (mysql_query($sql)) {
			$this->error = $this->messages(11);
		} else {
			$this->error = $this->messages(1);
		}	
	}
	// update/replace a single order row with the new quantity
	function update_row($row_id, $quantity, $replace = "yes") {
		if ($quantity == 0) {
			$this->delete_row($row_id);
		} else {
			$new_quant = ($replace == "no") ? "quantity + ".$quantity : $quantity;
			$sql = sprintf("UPDATE %s SET quantity = %s WHERE id = %d AND order_id = %d", ORDER_ROWS, $new_quant, $row_id, $_SESSION['order_id']);
			if (mysql_query($sql)) {
				$this->error = $this->messages(12);
			} else {
				$this->error = $this->messages(1);
			}
		}
	}
	// function to delete a single row
	function delete_row($row_id) {
		$sql = sprintf("DELETE FROM %s WHERE id = %d AND order_id = %d", ORDER_ROWS, $row_id, $_SESSION['order_id']);
		if (mysql_query($sql)) {
			$this->error = $this->messages(15);
		} else {
			$this->error = $this->messages(1);
		}	
	}
	// handle a order row while using the methodes above
	function handle_cart_row($prod_id, $prod_name, $quantity, $price, $replace = "no", $vat_amount = VAT_VALUE) {
		$check_row = $this->check_existing_row($prod_id);
		if ($check_row == "old") {
			$this->update_row($this->curr_product, $quantity, $replace);
		} elseif ($check_row == "new") {
			$this->insert_row($prod_id, $prod_name, $quantity, $price, $vat_amount);
		} else {
			$this->error = $this->messages(13);
		}
	}
	// get the number of ordered rows which belong to this customer
	function get_number_of_records() {
		$sql = sprintf("SELECT COUNT(*) AS num FROM %s AS r, %s AS ord WHERE ord.id = r.order_id AND ord.id = %d AND ord.open = 'y'", ORDER_ROWS, ORDERS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			return mysql_result($result, 0, "num");
		} else {
			$this->error = $this->messages(1);
			return;
		}
	}
	// get all order rows from the DB and store them in to an array
	function show_ordered_rows() {
		$sql = sprintf("SELECT r.id, r.product_id, r.product_name, r.price, r.vat_perc, r.quantity FROM %s AS r, %s AS ord WHERE ord.id = r.order_id AND ord.id = %d AND ord.open = 'y'", ORDER_ROWS, ORDERS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			if (mysql_num_rows($result) > 0) {
				$counter = 0;
				while ($row = mysql_fetch_assoc($result)) {
					foreach($row as $key => $val) {
						$this->order_array[$counter][$key] = $val;
					}
					$counter++;
				}
			} else {
				$this->error = $this->messages(14);
			}
		} else {
			$this->error = $this->messages(1);
		}
	}
	// show total value of the current cart
	function show_total_value() {
		$sql = sprintf("SELECT SUM(quantity * price) AS total FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']);
		if (!$result = mysql_query($sql)) {
			$this->error = $this->messages(1);
			return;
		} else {
			$total_amount = mysql_result($result, 0, "total");
			mysql_free_result($result);
			return $total_amount;
		}
	}
	// calculate VAT, switch between true and false to handle netto or brutto prices
	function create_total_VAT() {
		$sql = sprintf("SELECT price, vat_perc, quantity FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']);
		if (!$result = mysql_query($sql)) {
			$this->error = $this->messages(1);
		} else {
			$vat = 0;
			if (mysql_num_rows($result) > 0) {
				while ($obj = mysql_fetch_object($result)) {
					$vat_dec = $obj->vat_perc / 100;
					if (INCL_VAT) {
						$vat = $vat + ($obj->price * $obj->quantity) / (1 + $vat_dec) * $vat_dec;
					} else {
						$vat = $vat + ($obj->price * $obj->quantity) * $vat_dec;
					}
				}
			}
		}
		mysql_free_result($result);
		return $vat;
	}
	// check if already an shipment record exist, if yes return the data
	function check_return_shipment() {
		$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			if (mysql_result($result, 0, "test") == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			$this->error = $this->messages(1);
			return false; 
		}
	}
	// read the current shipment data and set the variabels
	function set_shipment_data() {
		if (!$this->check_return_shipment()) { // create an empty record if there is no shipment data
			$this->insert_new_shipment();
		}
		$sql = sprintf("SELECT * FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			$obj = mysql_fetch_object($result);
			$this->ship_name = $obj->name;
			$this->ship_address = $obj->address;
			$this->ship_pc = $obj->postal_code;
			$this->ship_city = $obj->place;
			$this->ship_country = $obj->country;
			$this->ship_msg = $obj->message;
		} else {
			$this->error = $this->messages(1);
		}
	}
	// function to insert a new shipment record
	function insert_new_shipment() {
		$sql = sprintf("INSERT INTO %s (order_id, name, address, postal_code, place, country) VALUES (%d, %s, %s, %s, %s, %s)",
			SHIP_ADDRESS, 
			$_SESSION['order_id'],
			$this->prepare_string_value($this->ship_name), 
			$this->prepare_string_value($this->ship_address), 
			$this->prepare_string_value($this->ship_pc), 
			$this->prepare_string_value($this->ship_city), 
			$this->prepare_string_value($this->ship_country));
		if (!mysql_query($sql)) {
			$this->error = $this->messages(1);
		}
	}
	// insert/update the shipment address vor the current order
	function update_shipment($name, $address, $postal, $city, $country, $msg) {
		if ($this->check_return_shipment()) {
			$sql = sprintf("UPDATE %s SET name = %s, address = %s, postal_code = %s, place = %s, country = %s, message = %s WHERE order_id = %d", 
				SHIP_ADDRESS, 
				$this->prepare_string_value($name), 
				$this->prepare_string_value($address), 
				$this->prepare_string_value($postal), 
				$this->prepare_string_value($city), 
				$this->prepare_string_value($country),
				$this->prepare_string_value($msg),
				$_SESSION['order_id']);
			if (mysql_query($sql)) {
				$this->error = $this->messages(21);
			} else {
				$this->error = $this->messages(1);
			}
		}	
	}
	// this method will delete all records for the current order and the visitor is redirect to the main page
	function cancel_order() {
		$err_level = 0;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']))) $err_level++;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']))) $err_level++;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE id = %d", ORDERS, $_SESSION['order_id']))) $err_level++;
		if ($err_level > 0) {
			$this->error = $this->messages(1);
		} else {
			unset($_SESSION['order_id']);
			header("Location: ".PROD_IDX);
		}	
	}
	// use the property $show_address to show/hide the shipment in the confirmation mail
	function check_out($mailto, $show_address = true) {
		$this->set_shipment_data();
		if ($this->mail_order($mailto, $show_address)) {
			header("Location: ".CONFIRM);
		} else {
			$this->error = $this->messages(2);
		}
	}
	function mail_order($to, $show_shipment) { 
		$header = "From: \"".SITE_MASTER."\" <".SITE_MASTER_MAIL.">\r\n";
		$header .= "Cc: ".SITE_MASTER_MAIL."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/plain; charset=\"".MAIL_ENCODING."\"\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n";
		$subject =  $this->messages(51); //your mailsubject incl. current date
		$body = $this->messages(52); // the body begin text
		$this->show_ordered_rows(); // rows in the order_array
		// next all order rows
		$body .= $this->messages(56); // the table header
		foreach ($this->order_array as $val) {
			$body .= sprintf("\t%-10s  %-30s  %-20s  %-12s\r\n", $val['quantity'], $val['product_name'], $val['product_id'], $this->format_value($val['price'], false));
		}
		$body .= $this->messages(53); // the total amount
		$body .= $this->messages(54); // the VAT amount
		$body .= ($show_shipment) ? $this->messages(57) : ""; // shipment adres...
		$body .= $this->messages(58); // the message.
		$body .= $this->messages(55); // kind regards...
		if (mail($to, $subject, $body, $header)) {
			return true;
		} else {
			return false;
		}
	}
	function close_order() {
		$sql = sprintf("UPDATE %s SET processed_on = NOW(), open = 'n' WHERE id = %d", ORDERS, $_SESSION['order_id']);
		if (mysql_query($sql)) {
			$this->error = $this->messages(22);
			unset($_SESSION['order_id']);
		} else {
			$this->error = $this->messages(1);
		}
	}
	// format a decimal number into an euro amount 
	// $encoding is used for the browser and 
	function format_value($value, $encoding = true) {
		if ($encoding) {
			$curr = (ord(CURRENCY) == "128") ? "&#8364;" : htmlentities(CURRENCY);
		} else {
			$curr= CURRENCY;
		}
		$formatted = $curr." ".number_format($value, 2, ",", ".");
		return $formatted;
	}
	// simple string preperation to prepend SQL injections
	function prepare_string_value($value) {
		$new_value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
		$new_value = ($value != "") ? "'".trim($value)."'" : "''";
		return $new_value;
	}

}
?>