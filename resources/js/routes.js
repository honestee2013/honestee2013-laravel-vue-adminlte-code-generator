export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/products', component: require('./components/product/Products.vue').default },
    { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    { path: '/product/category', component: require('./components/product/Category.vue').default },
    
    // Generated routes comes below //
	
	//{ path: '/roles', component: require('./components/honestee/vuecodegen/Role.vue').default },
		
	{ path: '/schools', component: require('./components/honestee/vuecodegen/School.vue').default },
	{ path: '/role-users', component: require('./components/honestee/vuecodegen/UserRole.vue').default },
	{ path: '/user-roles', component: require('./components/honestee/vuecodegen/RoleUser.vue').default },
	{ path: '*', component: require('./components/NotFound.vue').default },
];

