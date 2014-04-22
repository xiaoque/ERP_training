# -*- coding: utf-8 -*-
##############################################################################
#
#    Second Hand Cars module for OpenERP
#    Copyright (C) 2013 Guillaume RIVIERE.
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
##############################################################################

from openerp.osv import osv
from openerp.osv import fields
from openerp.tools.translate import _
import time

class secondhandcars_brand(osv.osv):
    """ Brand of cars """
    _name = "secondhandcars.brand"
    _description = "Brands of cars"
    _columns = {
        'name': fields.char('Brand name', size=64, required=True),
	'inter_site': fields.char('International website', size=100, required=True),
	'local_site': fields.char('Local website', size=100, required=True),
	'mod_page': fields.char('Model page', size=100, required=True),
    }
    _sql_constraints = [
        ('name', 'unique(name)', 'The name of the brand must be unique')
    ]
    _order = 'name asc'

class secondhandcars_model(osv.osv):
    """ Model of cars """
    _name = "secondhandcars.model"
    _description = "Model of cars"
    _columns = {
        'name': fields.char('Model name', size=64, required=True),
	'brand_id': fields.many2one('secondhandcars.brand', 'Brand', required=True),
	'last_year': fields.integer('The last year of production', required=False),

    }
    _sql_constraints = [
        ('name', 'unique(name)', 'The name of the model must be unique')
    ]
    _order = 'name asc'

class secondhandcars_car(osv.osv):
    """cars """
    _name = "secondhandcars.car"
    _description = "cars"
    _columns = {
	'create_uid': fields.many2one('res.users', 'CreateCar', size=64, required=True,readonly=True),
        'immatriculation': fields.char('The immatriculation code of the car', size=64, required=True),
	'model_ids': fields.many2one('secondhandcars.model', 'Models', required=True),	
	'km_in': fields.integer('kilometers of the car when arriving at the garage', required=False),
	'km_out': fields.integer('kilometers of the car when leaving at the garage', required=False),
	'price': fields.float('The price the garage wants to sell the car', required=True),
	'doors': fields.integer('The number of doors', required=True),
	'seats': fields.integer('The number of seats', required=True),
	'energy': fields.selection([('Gasoline', 'Gasoline'),
                                   ('Diesel', 'Diesel'),
                                   ('Gaz', 'Gaz'),('Electricity','Electricity'),('Hybrid','Hybrid')],'The energy of the engine', 
                                   required=True),
    }
    _sql_constraints = [
        ('immatriculation', 'unique(immatriculation)', 'The immatriculation of the car must be unique')
    ]
    _order = 'immatriculation asc'


