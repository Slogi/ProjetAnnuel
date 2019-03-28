from __future__ import print_function
import mysql.connector
from mysql.connector import errorcode


class MysqlTest():
    conf = {
        'host': '127.0.0.1',
        'user': 'root',
        'password': '',
        'database': 'market_monitor',
        'raise_on_warnings': True
    }

    def __init__(self, **kwargs):
        self.cnx = self.mysql_connect()

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

    def select_item(self):
        cursor = self.cnx.cursor()
        select_query = "SELECT product_ean FROM product where product_ean = '8003437208454'"
        cursor.execute(select_query)
        rows = cursor.fetchall()
        print(len(rows))
        cursor.close()
        self.cnx.close()


def main():
    mysql = MysqlTest()
    mysql.select_item()


if __name__ == "__main__": main()