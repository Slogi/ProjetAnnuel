# -*- coding: utf-8 -*-
import scrapy
from scrapy.loader import ItemLoader
from scrapy.loader.processors import TakeFirst, Compose, MapCompose, Join

clean_text = Compose(MapCompose(lambda v: v.strip()), Join())
clean_price = Compose(MapCompose(lambda v: v.strip(',€')), Join())

class DartyItem(scrapy.Item):
    brand = scrapy.Field()
    product_name = scrapy.Field()
    ean = scrapy.Field()
    price_exponent = scrapy.Field()
    price_fraction = scrapy.Field()
    img = scrapy.Field()
    url = scrapy.Field()
    date = scrapy.Field()

class DartyItemLoader(ItemLoader):
    brand_out = TakeFirst()
    product_name_in = clean_text
    product_name_out = clean_text
    ean_out = TakeFirst()
    price_exponent_in = clean_price
    price_exponent_out = TakeFirst()
    price_fraction_in = clean_price
    price_fraction_out = TakeFirst()
    img_in = clean_text
    img_out = clean_text
    url_out = TakeFirst()
    date_out = TakeFirst()
