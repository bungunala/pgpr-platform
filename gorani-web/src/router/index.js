import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/auth/login',
    name: 'login',
    component: () => import('@/views/auth/LoginView.vue'),
    meta: { guest: true },
  },
  {
    path: '/auth/register',
    name: 'register',
    component: () => import('@/views/auth/RegisterView.vue'),
    meta: { guest: true },
  },
  {
    path: '/auth/verify-email/:token',
    name: 'verify-email',
    component: () => import('@/views/auth/VerifyEmailView.vue'),
    meta: { guest: true },
  },
  {
    path: '/events',
    name: 'events',
    component: () => import('@/views/events/EventsListView.vue'),
  },
  {
    path: '/events/:id',
    name: 'event-detail',
    component: () => import('@/views/events/EventDetailView.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/profile/ProfileView.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('@/views/admin/AdminDashboard.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        name: 'admin-events',
        component: () => import('@/views/admin/EventsListView.vue'),
      },
      {
        path: 'events/new',
        name: 'event-create',
        component: () => import('@/views/admin/EventFormView.vue'),
      },
      {
        path: 'events/:id',
        name: 'event-edit',
        component: () => import('@/views/admin/EventFormView.vue'),
      },
      {
        path: 'events/:id/schedule',
        name: 'event-schedule',
        component: () => import('@/views/admin/ScheduleView.vue'),
      },
      {
        path: 'events/:id/users',
        name: 'event-users',
        component: () => import('@/views/admin/EventUsersView.vue'),
      },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/NotFoundView.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } })
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'home' })
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router