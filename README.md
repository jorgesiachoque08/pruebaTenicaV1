Generar clave secreta
He incluido un comando auxiliar para generar una clave para usted:

php artisan jwt:secret
Esto actualizará su .envarchivo con algo comoJWT_SECRET=foobar

Es la clave que se utilizará para firmar sus tokens.


url :http://127.0.0.1:8000/api/auth/login 
parametros: clave,usuario
POST

url :http://127.0.0.1:8000/api/auth/registrar
parametros: clave,usuario
POST


url :http://127.0.0.1:8000/api/auth/asignarPerfil
parametros: rol
POST
Requiere login

url :http://127.0.0.1:8000/api/prestador/crearCita
parametros: descripcion,cupos_totales,fecha
POST
Requiere login y ser rol prestador

url :http://127.0.0.1:8000/api/prestador/listarCuposCita/{codCita}
parametros: codCita
GET
Requiere login y ser rol prestador


url :http://127.0.0.1:8000/api/solicitante/subscribirse
parametros: IdUserPrestador
POST
Requiere login y ser rol solicitante

url :http://127.0.0.1:8000/api/solicitante/listarCuposCita/{codCita}
parametros: codCita
GET
Requiere login y ser rol solicitante

url :http://127.0.0.1:8000/api/solicitante/apartarCupo
parametros: codCita
POST
Requiere login y ser rol solicitante

url :http://127.0.0.1:8000/api/solicitante/listarPrestadores
GET
Requiere login y ser rol solicitante

url :http://127.0.0.1:8000/api/solicitante/listarCitas
GET
Requiere login y ser rol solicitante

url :http://127.0.0.1:8000/api/prestador/listarCitas
GET
Requiere login y ser rol prestador