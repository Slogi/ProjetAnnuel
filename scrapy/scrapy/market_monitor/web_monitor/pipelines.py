# -*- coding: utf-8 -*-
from __future__ import print_function
import mysql.connector
from mysql.connector import errorcode
import datetime

class WebMonitorPipeline(object):
    conf = {
        'host': 'localhost',
        'user': 'root',
        'password': '',
        'database': 'marketmonitor',
        'raise_on_warnings': True
    }

    def __init__(self, **kwargs):
        self.cnx = self.mysql_connect()

    def open_spider(self, spider):
        print("spider open")

    def process_item(self, item, spider):
        print("Saving item into db ...")
        self.save(dict(item))
        return item

    def close_spider(self, spider):
        self.mysql_close()

    def mysql_connect(self):
        try:
            return mysql.connector.connect(**self.conf)
        except mysql.connector.Error as err:
            if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
                print("Something is wrong with your user name or password")
            elif err.errno == errorcode.ER_BAD_DB_ERROR:
                print("Database does not exist")
            else:
                print(err)

    def save(self, row):
        cursor_ean_exist = self.cnx.cursor(buffered=True)
        ean_exist = ("SELECT * FROM product WHERE product_ean = %(ean)s")
        cursor_ean_exist.execute(ean_exist, row)
        rows = cursor_ean_exist.fetchall()
        nb_result = len(rows)
        cursor_ean_exist.close()
        #Le produit est deja dans table product
        if nb_result > 0:
            print("----------------ALREADY IN BASE---------------")
            cursor_price_select = self.cnx.cursor(buffered=True)
            price_select = ("SELECT price_price_exponent, price_price_fraction FROM price WHERE price_ean = %(ean)s AND price_id_site = '01' ORDER BY price_date DESC LIMIT 1")
            cursor_price_select.execute(price_select, row)
            rows = cursor_price_select.fetchall()
            nb_result = len(rows)
            cursor_price_select.close()
            # Le produit est déja dans la table prix pour ce site, on verifie le prix
            if nb_result > 0:
                for r in rows:
                    # Le prix est different, on insert
                    if (r[0] != row['price_exponent']) or (r[1] != row['price_fraction']):
                        cursor_price_query = self.cnx.cursor(buffered=True)
                        price_query = ("INSERT INTO price (price_ean, price_id_site, price_date, price_price_exponent, price_price_fraction) VALUES (%(ean)s, '01', %(date)s, %(price_exponent)s, %(price_fraction)s     )")
                        cursor_price_query.execute(price_query, row)
                        self.cnx.commit()
                        cursor_price_query.close()
                    # Le prix est le meme, pas d'insert
                    else:
                        pass
            # Le produit n'est pas dans la table prix pour ce site
            else:
                cursor_price_query = self.cnx.cursor(buffered=True)
                price_query = ("INSERT INTO price (price_ean, price_id_site, price_date, price_price_exponent, price_price_fraction) VALUES (%(ean)s, '01', %(date)s, %(price_exponent)s, %(price_fraction)s     )")
                cursor_price_query.execute(price_query, row)
                self.cnx.commit()
                cursor_price_query.close()

            cursor_link_select = self.cnx.cursor(buffered=True)
            link_select = ("SELECT link_ean FROM link WHERE link_id_site = '01' AND link_ean = %(ean)s")
            cursor_link_select.execute(link_select, row)
            rows = cursor_link_select.fetchall()
            nb_result = len(rows)
            cursor_link_select.close()

            # Le produit est dans la table link pour ce site
            if nb_result > 0:
                pass
            else:
                cursor_link_query = self.cnx.cursor(buffered=True)
                link_query = ("INSERT INTO link (link_ean, link_id_site, link_url) VALUES (%(ean)s, '01', %(url)s)")
                cursor_link_query.execute(link_query, row)
                self.cnx.commit()
                cursor_link_query.close()

        #Le produit n'est pas dans la table product
        else:
            cursor_product_query = self.cnx.cursor(buffered=True)
            cursor_link_query = self.cnx.cursor(buffered=True)
            cursor_price_query = self.cnx.cursor(buffered=True)

            product_query = ("INSERT INTO product (product_ean, product_name, product_brand, product_img) VALUES (%(ean)s, %(product_name)s, %(brand)s, %(img)s)")
            link_query = ("INSERT INTO link (link_ean, link_id_site, link_url) VALUES (%(ean)s, '01', %(url)s)")
            price_query = ("INSERT INTO price (price_ean, price_id_site, price_date, price_price_exponent, price_price_fraction) VALUES (%(ean)s, '01', %(date)s, %(price_exponent)s, %(price_fraction)s     )")

            cursor_product_query.execute(product_query, row)
            cursor_link_query.execute(link_query, row)
            cursor_price_query.execute(price_query, row)
            self.cnx.commit()
            cursor_product_query.close()
            cursor_link_query.close()
            cursor_price_query.close()

    def mysql_close(self):
        self.cnx.close()




