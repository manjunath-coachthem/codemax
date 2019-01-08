import Home from './components/Home.vue';
import Login from './components/auth/Login.vue';

import CustomersMain from './components/customers/Main.vue';
import CustomersList from './components/customers/List.vue';
import NewCustomer from './components/customers/New.vue';
import Customer from './components/customers/View.vue';

import ManufacturerMain from './components/manufacturer/Main.vue';
import ManufacturerList from './components/manufacturer/List.vue';
import NewManufacturer from './components/manufacturer/New.vue';
import Manufacturer from './components/manufacturer/View.vue';

import ModelMain from './components/model/Main.vue';
import ModelList from './components/model/List.vue';
import NewModel from './components/model/New.vue';
import Model from './components/model/View.vue';

import InventoryMain from './components/inventory/Main.vue';
import InventoryList from './components/inventory/List.vue';

export const routes = [
	{
		path: '/',
		component: Home,
		meta: {
			requiresAuth: true
		}
	},
	{
		path: '/login',
		component: Login
	},
	{
		path: '/customers',
		component: CustomersMain,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: '/',
				component: CustomersList,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/customer/new',
				component: NewCustomer,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/customer/:id',
				component: Customer,
				meta: {
					requiresAuth: true
				}
			}
		]

	},
	{
		path: '/manufacturer',
		component: ManufacturerMain,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: '/',
				component: ManufacturerList,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/manufacturer/new',
				component: NewManufacturer,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/manufacturer/:id',
				component: Manufacturer,
				meta: {
					requiresAuth: true
				}
			}
		]

	},
	{
		path: '/model',
		component: ModelMain,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: '/',
				component: ModelList,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/model/new',
				component: NewModel,
				meta: {
					requiresAuth: true
				}
			},
			{
				path: '/model/:id',
				component: Model,
				meta: {
					requiresAuth: true
				}
			}
		]

	},
	{
		path: '/inventory',
		component: InventoryMain,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: '/',
				component: InventoryList,
				meta: {
					requiresAuth: true
				}
			}
		]

	}
];