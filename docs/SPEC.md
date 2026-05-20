# PGPR Platform - Especificaciones del Proyecto

**Versión:** 1.0  
**Fecha:** 13 de Mayo de 2026  
**Fecha objetivo de entrega:** 31 de Agosto de 2026  
**Tipo:** Aplicación de Producción Final

---

## 1. Descripción General del Proyecto

### 1.1 Nombre del Proyecto
**PGPR Platform** - Plataforma de Matchmaking B2B para ProEcuador

### 1.2 Propósito
Plataforma web para gestionar encuentros de negocios entre vendedores (exportadores) y compradores internacionales, incluyendo:
- Registro de empresas
- Gestión de eventos
- Matchmaking automático
- Agendamiento de citas
- Control de piso
- Generación de credenciales con QR

### 1.3 Usuarios Objetivo
- **Vendedores:** Empresas ecuatorianas que ofrecen productos/servicios
- **Compradores:** Empresas internacionales interesadas en productos ecuatorianos
- **Administradores:** Personal de ProEcuador que gestiona los eventos

### 1.4 Volumen Estimado
- 120 vendedores
- 50 compradores
- 170 usuarios totales

---

## 2. Stack Tecnológico

### 2.1 Backend
| Componente | Tecnología | Versión |
|------------|------------|---------|
| Framework | CodeIgniter | 4.x |
| Base de datos | PostgreSQL | 16 |
| Cache | Redis | 7 |
| Autenticación | JWT | firebase/php-jwt |
| Email | SwiftMailer | - |

### 2.2 Frontend
| Componente | Tecnología | Versión |
|------------|------------|---------|
| Framework | Vue.js | 3.x |
| Build tool | Vite | - |
| Estado | Pinia | 2.x |
| Router | Vue Router | 4.x |
| HTTP Client | Axios | 1.x |
| UI Framework | Bootstrap | 5.x |
| i18n | Vue I18n | 9.x |

### 2.3 Infraestructura
| Componente | Tecnología |
|------------|------------|
| Contenedores | Docker + Docker Compose |
| Servidor web | Nginx |
| SO | Alpine Linux |

---

## 3. Modelo de Datos

### 3.1 Entidades Principales

#### Users (Usuarios)
```sql
users (
    id              SERIAL PRIMARY KEY,
    email           VARCHAR(255) UNIQUE NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    email_verified  BOOLEAN DEFAULT FALSE,
    email_token     VARCHAR(100),
    token_expires   TIMESTAMP,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### Roles
```sql
roles (
    id   SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL  -- admin, seller, buyer
)

user_roles (
    user_id INT REFERENCES users(id),
    role_id INT REFERENCES roles(id),
    PRIMARY KEY (user_id, role_id)
)
```

#### User Profiles (Perfil de Usuario)
```sql
user_profiles (
    id               SERIAL PRIMARY KEY,
    user_id          INT UNIQUE REFERENCES users(id),
    identification   VARCHAR(20),  -- RUC (13 digitos para Ecuador)
    business_name    VARCHAR(200),
    phone            VARCHAR(20),
    mobile           VARCHAR(20),
    address          TEXT,
    website          VARCHAR(200),
    foundation_year  VARCHAR(4),
    employees_number VARCHAR(20),
    country_code     VARCHAR(10),
    city             VARCHAR(100),
    description_es   TEXT,
    description_en   TEXT,
    logo_url         VARCHAR(500),
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### Events (Eventos)
```sql
events (
    id                   SERIAL PRIMARY KEY,
    name_es              VARCHAR(200) NOT NULL,
    name_en              VARCHAR(200),
    description_es       TEXT,
    description_en       TEXT,
    image_url            VARCHAR(500),
    registration_start   DATE NOT NULL,
    registration_end     DATE NOT NULL,
    schedule_start       DATE,
    schedule_end         DATE,
    schedule_mode        VARCHAR(20) DEFAULT 'request',  -- 'request' | 'confirmed'
    status_registration  BOOLEAN DEFAULT FALSE,
    status_schedule      BOOLEAN DEFAULT FALSE,
    credentials_enabled  BOOLEAN DEFAULT FALSE,
    has_desk_numbers     VARCHAR(20) DEFAULT 'none',  -- 'none', 'seller', 'buyer'
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

event_sectors (event_id, sector_id)
```

#### Event Days (Días del Evento)
```sql
event_days (
    id         SERIAL PRIMARY KEY,
    event_id   INT REFERENCES events(id),
    day_number INT NOT NULL,
    name_es    VARCHAR(100) NOT NULL,  -- "Día 1", "Día 2"
    name_en    VARCHAR(100),
    date       DATE NOT NULL
)
```

#### Event Schedules (Horarios del Cronograma)
```sql
event_schedules (
    id           SERIAL PRIMARY KEY,
    event_day_id INT REFERENCES event_days(id),
    slot_number  INT NOT NULL,
    name_es      VARCHAR(50) NOT NULL,   -- "Cita 1", "Almuerzo", "Cita 5"
    name_en      VARCHAR(50),
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    duration_minutes INT DEFAULT 30,
    is_break    BOOLEAN DEFAULT FALSE   -- TRUE para almuerzos/coffee break
)
```

#### Event Registrations (Inscripción en Evento)
```sql
event_registrations (
    id             SERIAL PRIMARY KEY,
    user_id        INT REFERENCES users(id),
    event_id       INT REFERENCES events(id),
    role           VARCHAR(20) NOT NULL,  -- 'seller', 'buyer', 'both'
    desk_number    VARCHAR(20),  -- numero de mesa (si aplica)
    status         VARCHAR(20) DEFAULT 'pending',  -- pending, approved, rejected
    approved_by    INT,
    approved_at    TIMESTAMP,
    registered_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, event_id)
)
```

#### Products (Productos - Vendedor)
```sql
products (
    id                   SERIAL PRIMARY KEY,
    user_id              INT REFERENCES users(id),
    event_id             INT REFERENCES events(id),
    type                 VARCHAR(20) NOT NULL,  -- 'product', 'service'
    subpartida_id        INT,
    subpartida_code      VARCHAR(20),
    subpartida_desc      VARCHAR(255),
    sector_id            INT REFERENCES sectors(id),
    name                 VARCHAR(255) NOT NULL,
    characteristics_es   TEXT,
    characteristics_en   TEXT,
    offer_capacity       DECIMAL(15,2),
    unit_code            VARCHAR(10),
    other_certificates   TEXT,
    photo_url            VARCHAR(500),
    datasheet_url        VARCHAR(500),
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

product_certificates (product_id, certificate_id)
product_export_countries (product_id, country_code)
```

#### Buyer Interests (Intereses - Comprador)
```sql
buyer_interests (
    id           SERIAL PRIMARY KEY,
    user_id      INT REFERENCES users(id),
    event_id     INT REFERENCES events(id),
    sector_id    INT REFERENCES sectors(id),
    description  TEXT,  -- producto buscado
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### Contacts (Contactos)
```sql
contacts (
    id         SERIAL PRIMARY KEY,
    user_id    INT REFERENCES users(id),
    greeting_code VARCHAR(10),
    first_name VARCHAR(100) NOT NULL,
    last_name  VARCHAR(100) NOT NULL,
    position   VARCHAR(100),
    email      VARCHAR(255) NOT NULL,
    phone      VARCHAR(50),
    mobile     VARCHAR(50),
    skype      VARCHAR(100)
)

contact_languages (contact_id, language_id)
event_contacts (contact_id, event_id)
```

#### Meetings (Citas/Reuniones)
```sql
meetings (
    id                       SERIAL PRIMARY KEY,
    event_id                 INT REFERENCES events(id),
    seller_user_id           INT REFERENCES users(id),
    buyer_user_id            INT REFERENCES users(id),
    schedule_id              INT REFERENCES event_schedules(id),
    day_id                   INT REFERENCES event_days(id),
    status                   VARCHAR(20) DEFAULT 'pending',  -- pending, accepted, rejected, cancelled, rescheduled
    
    requested_by             INT REFERENCES users(id),
    requested_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_by             INT,
    responded_at             TIMESTAMP,
    
    alternative_day_id       INT,
    alternative_schedule_id  INT,
    
    accepted_by              INT,
    accepted_at              TIMESTAMP,
    rejected_by              INT,
    rejected_at              TIMESTAMP,
    
    cancellation_reason      TEXT,
    cancelled_by             INT,
    cancelled_at             TIMESTAMP
)
```

#### Credentials (Credenciales)
```sql
credentials (
    id              SERIAL PRIMARY KEY,
    event_id        INT REFERENCES events(id),
    user_id         INT REFERENCES users(id),
    contact_id      INT REFERENCES contacts(id),
    type            VARCHAR(20),  -- 'seller', 'buyer', 'staff'
    desk_number     VARCHAR(20),
    qr_code         VARCHAR(255),
    printed_at      TIMESTAMP,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

#### Email Templates (Plantillas de Email)
```sql
email_templates (
    id        SERIAL PRIMARY KEY,
    event_id  INT REFERENCES events(id),
    code      VARCHAR(50) NOT NULL,
    subject_es VARCHAR(500),
    subject_en VARCHAR(500),
    body_es    TEXT,
    body_en    TEXT
)
```

#### Catálogos
```sql
-- Países
catalog_countries (code PK, name_es, name_en)

-- Idiomas
catalog_languages (id, name_es, name_en)

-- Certificados
catalog_certificates (id, name_es, name_en)

-- Saludos
catalog_greetings (code PK, name_es, name_en)

-- Unidades
catalog_units (code PK, name_es, name_en)

-- Sectores
sectors (id, name_es, name_en)

-- Sub-sectores
sub_sectors (id, sector_id, name_es, name_en)

-- Subpartidas arancelarias (mockup WS)
subpartidas (id, code, description_es, description_en)
```

---

## 4. API Endpoints

### 4.1 Autenticación
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/auth/register` | Registro inicial |
| POST | `/api/auth/verify-email/{token}` | Verificar email |
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/me` | Usuario actual |
| PUT | `/api/auth/password` | Cambiar contraseña |

### 4.2 Perfil
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/profile` | Obtener perfil |
| PUT | `/api/profile` | Actualizar perfil |
| POST | `/api/profile/logo` | Subir logo |

### 4.3 Eventos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/events` | Listar eventos |
| GET | `/api/events/{id}` | Detalle evento |
| GET | `/api/events/my-registrations` | Mis inscripciones |
| POST | `/api/events/{id}/register` | Inscribirse |
| GET | `/api/events/{id}/schedule` | Ver cronograma |

### 4.4 Admin - Eventos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/admin/events` | Crear evento |
| PUT | `/api/admin/events/{id}` | Editar evento |
| PUT | `/api/admin/events/{id}/status` | Cambiar estado |
| PUT | `/api/admin/events/{id}/credentials` | Habilitar credenciales |
| POST | `/api/admin/events/{id}/days` | Agregar dia |
| PUT | `/api/admin/events/{id}/days/{did}` | Editar dia |
| POST | `/api/admin/events/{id}/days/{did}/slots` | Agregar horarios |
| PUT | `/api/admin/events/{id}/slots/{sid}` | Editar horario |
| DELETE | `/api/admin/events/{id}/slots/{sid}` | Eliminar horario |

### 4.5 Productos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/products` | Listar productos |
| POST | `/api/products` | Crear producto |
| PUT | `/api/products/{id}` | Actualizar |
| DELETE | `/api/products/{id}` | Eliminar |
| GET/POST/DELETE | `/api/events/{id}/products` | Asociar al evento |

### 4.6 Intereses (Comprador)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/interests` | Listar intereses |
| POST | `/api/interests` | Crear |
| PUT | `/api/interests/{id}` | Actualizar |
| DELETE | `/api/interests/{id}` | Eliminar |

### 4.7 Contactos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/contacts` | Listar contactos |
| POST | `/api/contacts` | Crear contacto |
| PUT | `/api/contacts/{id}` | Actualizar |
| DELETE | `/api/contacts/{id}` | Eliminar |

### 4.8 Matchmaking
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/events/{id}/search` | Buscar contrapartes |
| GET | `/api/events/{id}/sellers` | Lista vendedores |
| GET | `/api/events/{id}/buyers` | Lista compradores |

### 4.9 Reuniones/Citas
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/events/{id}/meetings` | Todas las citas |
| GET | `/api/events/{id}/meetings/pending` | Pendientes por confirmar |
| GET | `/api/events/{id}/meetings/requested` | Solicitadas por mi |
| GET | `/api/events/{id}/meetings/schedule` | Agenda confirmada |
| POST | `/api/events/{id}/meetings` | Solicitar cita |
| PUT | `/api/events/{id}/meetings/{mid}/accept` | Aceptar |
| PUT | `/api/events/{id}/meetings/{mid}/accept-alternative` | Aceptar otro horario |
| PUT | `/api/events/{id}/meetings/{mid}/reject` | Rechazar |
| DELETE | `/api/events/{id}/meetings/{mid}` | Cancelar |
| GET | `/api/events/{id}/available-slots/{user_id}` | Horarios disponibles |

### 4.10 Admin - Usuarios
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/admin/events/{id}/users` | Usuarios del evento |
| PUT | `/api/admin/events/{id}/users/{uid}/approve` | Aprobar |
| PUT | `/api/admin/events/{id}/users/{uid}/reject` | Rechazar |

### 4.11 Admin - Reportes
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/admin/events/{id}/reports/sellers` | Reporte vendedores |
| GET | `/api/admin/events/{id}/reports/buyers` | Reporte compradores |
| GET | `/api/admin/events/{id}/reports/agenda` | Reporte agenda |

### 4.12 Credenciales
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/credentials/{id}` | Ver datos credencial |
| GET | `/api/credentials/{id}/qr` | Generar QR |
| GET | `/api/events/{id}/credentials/print` | Imprimir credencial |
| POST | `/api/admin/credentials/staff` | Crear credencial staff |

### 4.13 Control de Piso
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/events/{id}/floor-control/current-slot` | Slot actual |
| GET | `/api/events/{id}/floor-control/itinerary` | Itinerario actual |
| POST | `/api/floor-control/checkin` | Check-in de contacto |

### 4.14 Catálogos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/catalogs/countries` | Países |
| GET | `/api/catalogs/languages` | Idiomas |
| GET | `/api/catalogs/certificates` | Certificados |
| GET | `/api/catalogs/greetings` | Saludos |
| GET | `/api/catalogs/units` | Unidades |
| GET | `/api/catalogs/sectors` | Sectores |
| GET | `/api/catalogs/sectors/{id}/sub-sectors` | Sub-sectores |
| GET | `/api/catalogs/subpartidas?q=` | Buscar subpartidas |

---

## 5. Reglas de Negocio

### 5.1 Eventos
- Un evento tiene nombre, descripción, imagen, fechas de registro, fechas de agendamiento y sectores objetivo
- Un evento contiene compradores y vendedores con números de mesa (configurable: compradores, vendedores o ninguno)
- Un evento NO puede pasar a estado "agendamiento" si no tiene cronograma cargado

### 5.2 Cronograma
- El cronograma debe tener días con descripción
- Cada día tiene horarios de citas configurables (duración variable)
- Los horarios especiales (almuerzo, coffee break, inauguración, cierre) no son citas (tipo "break")
- Interfaz de creación: primero crear día → luego agregar horarios

### 5.3 Usuarios
- Cada usuario tiene un perfil con: RUC (13 dígitos para Ecuador), nombre comercial, teléfono, celular, dirección, email, sitio web, año de creación, número de empleados, país, ciudad, descripción (ES/EN), logotipo
- En un evento, el usuario puede ser: vendedor, comprador o ambos
- El usuario puede cambiar su contraseña

### 5.4 Productos (Vendedor)
- Tipo: producto o servicio
- Subpartida arancelaria (viene de servicio web)
- Sector (de la BD)
- Nombre, características (ES/EN)
- Capacidad de oferta (cantidad + unidad)
- Certificaciones (selección múltiple)
- Otros certificados (texto libre)
- Países de exportación (selección múltiple)
- Foto (PNG/JPG)
- Ficha de producto (PDF)

### 5.5 Intereses (Comprador)
- Sector
- Producto buscado (texto libre)

### 5.6 Contactos
- Saludo, nombres, apellidos, cargo, email, teléfono, celular, idiomas (selección múltiple)

### 5.7 Registro
- El usuario ingresa su email y selecciona rol (vendedor/comprador/ambos)
- Si ya existe, carga su perfil; si es nuevo, muestra formulario
- Debe aceptar términos y condiciones
- Envía email de confirmación del registro en el evento (con imagen y descripción del evento)
- En el email de confirmación debe haber un link para "rechazar inscripción"
- Si es usuario nuevo, envía email de verificación que debe confirmar
- Al iniciar sesión, muestra lista de eventos donde está inscrito
- Si faltan productos/intereses/contactos por asociar, la interfaz lo indica

### 5.8 Agendamiento
- Al entrar debe seleccionar rol (si está inscrito como ambos)
- No puede verse a sí mismo si es vendedor y comprador en el mismo evento
- Búsqueda por: país, nombre de producto, nombre de empresa
- Al seleccionar empresa, muestra perfil + productos/intereses
- Puede solicitar cita si el horario está disponible para ambos
- Estados de cita:
  - Pendiente de confirmar
  - Citas solicitadas por mí
  - Cancelar citas confirmadas
  - Ver agenda (citas confirmadas)
- Datos de auditoría: quién solicita, quién aprueba, fechas

### 5.9 Credenciales
- Admin habilita impresión de credenciales para el evento
- Vendedor y comprador pueden imprimir su credencial
- Admin puede imprimir cualquier credencial
- Admin puede crear credenciales para staff (datos manuales)
- Contenido de credencial:
  - Nombre del contacto
  - Nombre de la empresa
  - País
  - Número de mesa
  - QR único

### 5.10 Código QR en Credenciales
- El QR debe mostrar los datos de la credencial
- Usado para control de piso (escanear credenciales)

### 5.11 Control de Piso
- Registrar si los contactos están en la sala
- Escaneo de credenciales en entradas/salidas
- Reloj de citas en pantallas (muestra número de cita actual + siguientes en lotes de 3)

### 5.12 Administración
- Cambiar estado de eventos (activar/desactivar registro y agendamiento)
- Revisar perfil, contactos y productos/intereses de usuarios
- Aprobar/rechazar usuarios para el evento
- Ver reportes: agenda completa, todos los vendedores, todos los compradores
- Habilitar impresión de credenciales

---

## 6. Multi-idioma

- Toda la aplicación debe funcionar en español e inglés
- Formularios con labels en ambos idiomas
- Selector de idioma en la interfaz
- Reportes configurables por idioma

---

## 7. Vistas del Frontend

### 7.1 Autenticación
- `/auth/login`
- `/auth/register` (email + rol inicial)
- `/auth/verify-email/{token}`
- `/auth/forgot-password`
- `/auth/reject-registration/{token}` (link en email)

### 7.2 Dashboard
- `/` - Dashboard (mis eventos)
- `/profile` - Mi perfil
- `/profile/password` - Cambiar contraseña

### 7.3 Eventos
- `/events` - Lista de eventos públicos
- `/events/{id}` - Dashboard del evento
- `/events/{id}/products` - Mis productos
- `/events/{id}/interests` - Mis intereses
- `/events/{id}/contacts` - Mis contactos
- `/events/{id}/credential` - Imprimir credencial

### 7.4 Agendamiento
- `/events/{id}/matchmaking/{role}` - Buscar contrapartes
- `/events/{id}/meetings` - Mis reuniones

### 7.5 Admin
- `/admin` - Dashboard admin
- `/admin/events` - Gestión de eventos
- `/admin/events/new` - Crear evento
- `/admin/events/{id}` - Editar evento
- `/admin/events/{id}/schedule` - Gestionar cronograma
- `/admin/events/{id}/users` - Aprobar usuarios
- `/admin/events/{id}/reports` - Reportes
- `/admin/events/{id}/credentials` - Gestión de credenciales
- `/admin/floor-control/{event_id}` - Control de piso
- `/admin/credentials/staff` - Crear credencial staff

---

## 8. Cronograma de Implementación

| Semana | Módulo |
|--------|--------|
| 1-2 | Setup Docker + CI4 + Vue.js + PostgreSQL |
| 2-3 | Migrations + Seeders + Auth JWT |
| 3-4 | API Perfiles + Eventos + Catálogos |
| 4-5 | API Productos + Intereses + Contactos |
| 5-7 | API Matchmaking + Reuniones |
| 7-9 | API Admin + Reportes + Credenciales + Control de Piso |
| 6-8 | Frontend Auth + Dashboard |
| 8-9 | Frontend Perfil + Productos + Contactos |
| 9-10 | Frontend Matchmaking + Reuniones |
| 10-12 | Frontend Admin + Cronograma + Credenciales + Control de Piso |
| 13-14 | Testing + Bug fixes |
| 15 | Documentación + Entrega |

---

## 9. Costos Estimados

| Categoría | Costo USD |
|-----------|-----------|
| Desarrollo (820 horas) | $28,700 - $42,000 |
| Documentación | $5,950 - $7,550 |
| Infraestructura (1 año) | $2,340 |
| **Total** | **$37,000 - $52,000** |

---

## 10. Notas

- Este documento es la especificación técnica del proyecto
- Cualquier cambio en alcance debe ser documentado
- Las reglas de negocio descritas son obligatorias
- El proyecto debe estar listo antes del 31 de agosto de 2026