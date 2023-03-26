
```
Curso       : 202223, 201920
Area        : Sistemas operativos, monitorización, devops
Descripción : Practicar test de infraestructura
Requisitos  : Varias máquinas. Una al menos con GNU/Linux
Tiempo      : 3 sesiones preparativos
              4 sesiones realizar la práctica
```

# 1.Test de infrastructura

Propuesta de rúbrica:

| Criterio        | Muy bien(2) | Regular(1) | Poco adecuado(0) |
| --------------- | ----------- | ---------- | ---------------- |
| (2.2) Comprobar ||||
| (3.2) Comprobar ||||
| (4.2) Comprobar ||||
| (5.2) Comprobar ||||
| (6.2) Comprobar |||.|

## 1.1 Introducción

* ¿Qué son los test de infraestructura?
* ¿Qué significa ver la infraestructura como código (IaC)?

Comparativa de varios test de infraestructura:

| Features | Teuton | Testinfra | Goss |
| -------- | ------ | --------- | ---- |
| URL      | https://github.com/teuton-software/teuton | https://testinfra.readthedocs.io/en/latest/index.html | https://github.com/aelsabbahy/goss |
| License  | GPL v.3 | Apache license 2.0 | Apache license 2.0 |
| Programming language | Ruby | Python | Go |
| Platforms | Multiplatform | Multiplatform | GNU/Linux |
| Remote connections | SSH, Telnet | SSH | ? |
| Installed on | Master | Slave |  |
| Test Units Lenguage definition | Teuton DSL | Python | YAML |
| Configuration input files | YAML, JSON | Python | YAML |
| Output formats      | Documentation, TXT, YAML, JSON, silent | ? | rspecish, documentation, JSON, TAP, JUnit, nagios, silent |
| Builtin functions | Yes | Yes | ... |
| Function creation | Yes | ?   | ? |

> TODO: Incluir Inspec en la comparativa.

## 1.2 Preparativos

Listado de las máquinas que vamos a necesitar:

| ID | Sistema  | Hostname     | IP           |
| -- | -------- | ------------ | ------------ |
|  1 | [OpenSUSE](../../global/configuracion/opensuse.md) | apellidoXXg  | 172.AA.XX.31 |
|  2 | [OpenSUSE](../../global/configuracion/opensuse.md) | apellidoXXg2 | 172.AA.XX.32 |
|  3 | [RbPI](../../global/configuracion/rbpi.md)     | apellidoXXrb | 172.AA.XX.51 |
|  4 | [Windows](../../global/configuracion/windows.md)  | apellidoXXw  | 172.AA.XX.11 |

Configurar en todas las máquinas:
* IP estática.
* Activar servicio SSH.
* Habilitar acceso SSH a root (Modificar fichero de configuración con `PermitRootLogin Yes`).
* Comprobar acceso remoto SSH con el comando `ssh root@IP-DE-LA-MV`.

## 1.3 Modos de trabajo

> Enlaces de interés:
> * [Modos de uso](https://github.com/teuton-software/teuton/blob/master/docs/install/modes_of_use.md)

* Entender los modos de trabajo de Teuton y la diferencia entre T-NODE y S-NODE.
* T-NODE: Máquina con Teuton.
* S-NODE: Máquina con el servicio SSH ([Configurar el servicio SSH](https://github.com/dvarrui/libro-de-actividades/blob/master/actividades/global/acceso-remoto/))

## 1.4 Instalación

> Enlace de interés:
> * [Instalación](https://github.com/teuton-software/teuton/blob/master/docs/install/README.md)

Vamos a ver el proceso de instalación de `teuton` en la máquina T-NODE:

Entrar como superusuario.
* `ruby -v` para comprobar la versión de ruby ( >= 2.5.9). En caso contrario instalar ruby.
* `gem install teuton`, instalar Teuton (Necesitaremos tener privilegios de root para instalar).
* `gem info teuton`, para comprobar que lo tenemos instalado.

> **ADVERTENCIA**: En OpenSUSE es necesario además es necesario hacer los siguiente:
> * `find /usr -name teuton`, para localizar el ejecutable.
> * `ln -s PATH/TO/FILE/teuton /usr/local/bin/teuton`, crear un enlace al ejecutable.

Entrar como nuestro usuario normal:
* `teuton version`, comprobar versión (>= 2.4.5).

## 1.5 Entregar

En esta práctica NO hay que hacer informe. Sólo hay que entregar un fichero comprimido zip con el contenido de las siguientes carpetas:
* `towerXX/*`
* `var/*`

> Recordar que XX es un identificador asociado a cada alumno.

# 2. Test: conectividad

## 2.1 Crear el test

Ir a la MV1:
* A partir de ahora trabajaremos con nuestro usuario habitual (no usar root).
* Ir al directorio `Documentos` para trabajar ahí.
* `teuton new towerXX/test2`, para crear los ficheros para nuestro test. Los ficheros principales son:
    * `config.yaml`, fichero de configuración de las máquinas
    * `start.rb`, definición de las unidades de prueba.
* Modificar `config.yaml` para incluir todas las máquinas que queremos monitorizar:

```yaml
---
:global:
:cases:
- :tt_members: teuton-XX
  :host_ip: 127.0.0.1
- :tt_members: GNU/Linux-XX
  :host_ip: 172.18.XX.32
- :tt_members: RaspberryPI-XX
  :host_ip: 172.18.XX.51
- :tt_members: Windows-XX
  :host_ip: 172.18.XX.11
```

* Vamos a modificar `start.rb` para preparar un test de conectividad con las máquinas:

```ruby
group "alumnoXX - test2" do

  target "Comprobar la conectividad"
  run "ping -c 1 #{get(:host_ip)}"
  expect " 0% packet loss"

end

play do
  show
  export
end
```

**Explicación:**
* [target](https://github.com/teuton-software/teuton/blob/devel/docs/dsl/definition/target.md): se usa para iniciar la definición de un "objetivo" de motnitorización. Debemos poner una descripción que nos ayude a identificar cuál es ese objetivo.
* [run](https://github.com/teuton-software/teuton/blob/devel/docs/dsl/definition/run.md): Indica el comando queremos ejecutar en la máquina local. Esta será la máquina T-Node, donde tenemos instalado Teutón.
* [expect](https://github.com/teuton-software/teuton/blob/devel/docs/dsl/definition/expect.md): Comprueba que la salida del comando anterior (run) contenga el texto que esperamos.

A continuación vemos una imagen de ejemplo, donde tenemos:
1. En verde la salida de un comando que se ejecuta dando la salida que esperamos (`expect " 0% packet loss"`).
1. En rojo la salida de un comando que se ejecuta dando una salida que NO esperamos.

![](images/teuton-ping.png)


## 2.2 Comprobar

* **Sintaxis**: `teuton check towerXX/test2`, nos hace una revisión de la sintaxis de los ficheros `config.yaml` y `start.rb` por si hemos escrito algo mal.
* **Ejecución**: `teuton towerXX/test2`, ejecutar el test.
* **Resultados**: Tenemos los resultados en el directorio `var/test2`. Comprobar que los resultados son los correctos.

# 3. Test: Configuración de red

## 3.1 Crear el test

* Crear el test `towerXX/test3`.
* Personalizar el fichero de configuración (`config.yaml`):

```yaml
---
:global:
  :host_username: root
:cases:
- :tt_members: teuton-XX
  :host_ip: 127.0.0.1
  :host_password: clave-secreta
- :tt_members: GNU/Linux-XX
  :host_ip: 172.18.XX.32
  :host_password: clave-secreta
- :tt_members: RaspberryPI-XX
  :host_ip: 172.18.XX.51
  :host_password: clave-secreta
- :tt_members: Windows-XX
  :tt_skip: true
  :host_ip: 172.18.XX.11
  :host_password: clave-secreta
```

**Explicación**. Nos fijamos que se han añadido los siguientes parámetros:

| Parámetro     | Descripción |
| ------------- | ----------- |
| host_username | será el nombre del usuario que usaremos para conectarnos de forma remota a las máquinas vía SSH |
| host_ip       | será valor de IP de la máquina a la que vamos a conectarnos de forma remota vía SSH |
| tt_skip       | si se pone a true estamos indicando que esta máquina no la vamos a comprobar por ahora. De momento vamos a excluir (skip==true) la máquina Windows de la monitorización, porque los comandos son diferentes. Lo arreglaremos más adelante.|

* Vamos a modificar `start.rb` para comprobar lo siguiente en las máquinas remotas:
    * Puerta de enlace: `ping -c 1 8.8.4.4`
    * Servidor DNS: `host www.nba.com`

```ruby
group "alumnoXX - test3" do

  target "La puerta de enlace funciona correctamente"
  run "ping -c 1 8.8.4.4", on: :host
  expect " 0% packet loss"

  target "Servidor DNS funciona correctamente"
  run "host www.nba.com", on: :host
  expect "has address"

end

play do
  show
  export
end
```

**Explicación:**

* [run](https://github.com/teuton-software/teuton/blob/master/docs/dsl/definition/run_remote.md): La sentencia "run" ejecuta un comando en una máquina remota. La conexión con la máquina remota se realiza usando el protocolo SSH.
* Cuando ejecutamos el comando `host www.nba.com` de forma correcta, obtenemos una salida como la siguiente, donde se obtiene al menos una línea con el texto `has address`:

```    
> host www.nba.com

www.nba.com has address 104.126.107.194
www.nba.com has IPv6 address 2a02:26f0:13c:396::2e1
www.nba.com has IPv6 address 2a02:26f0:13c:38b::2e1
www.nba.com is an alias for nbaevsecure.edgekey.net.
nbaevsecure.edgekey.net is an alias for e737.dscg.akamaiedge.net.
```
* Cuando ejecutamos el comando `host www.enebea66.com` y es incorrecto, comprobamos que la salida no muestra ninguna línea del tipo `has address`:

```
> host www.enebea66.com

Host www.enebea66.com not found: 3(NXDOMAIN)
```

## 3.2 Comprobar

* **Sintaxis**. `teuton check castleXX/test3`, nos hace una revisión de la sintaxis de los ficheros `config.yaml` y `start.rb` por si hemos escrito algo mal. En tal caso, hay que revisar el apartado anterior.
* **Ejecución**. `teuton castleXX/test3`, ejecutar el test.
* **Resultados**. Tenemos los resultados en el directorio `var/test3`. Comprobar que los resultados son los correctos.

# 4. Test: configuración básica

## 4.1 Modificar el test

* Copiar test3 en `towerXX/test4`.
* Ampliar los "targets" para evaluar lo siguiente en las máquinas remotas:
    * Nombre de equipo: `hostname`
    * Usuario alumno: `id nombre-alumno`
* Modificar el fichero de configuración para incluir nuevos parámetros (`hostname` y `username`):

```yaml
---
:global:
  :host_username: root
  :username: nombre-del-alumno
:cases:
- :tt_members: teuton-XX
  :host_ip: localhost
  :host_password: clave-secreta
  :hostname: apellidoXXg
- :tt_members: GNU/Linux-XX
  :host_ip: 172.18.XX.32
  :host_password: clave-secreta
  :hostname: apellidoXXg2
- :tt_members: RaspberryPI-XX
  :host_ip: 172.18.XX.51
  :host_password: clave-secreta
  :hostname: apellidoXXrb
- :tt_members: Windows-XX
  :tt_skip: true
  :host_ip: 172.18.XX.11
  :host_password: clave-secreta
  :hostname: apellidoXXw
```

Veamos un ejemplo de monitorización del nombre del equipo:

```ruby

  target "Comprobar el nombre del equipo"
  run "hostname", on: :host
  expect get(:hostname)

```

## 4.2 Comprobar

* **Sintaxis**: `teuton test towerXX/test4`, nos hace una revisión de la sintaxis de los ficheros `config.yaml` y `start.rb` por si hemos escrito algo mal.
* **Ejecución**: `teuton towerXX/test4`, ejecutar el test.
* **Resultados**: Tenemos los resultados en el directorio `var/test4`. Comprobar que los resultados son los correctos.

# 5. Test: directorios y permisos

* Crear un nuevo test `towerXX/test5`.

Definir las comprobaciones necesarias en `start.rb`para:
* Comprobar que existe el grupo `jedis`.
* Comprobar que existe el usuario `obiwan`.
* Comprobar que `obiwan`es miembro del grupo `jedis`.
* Comprobar que existe el directorio `/home/obiwan/private`.
* Comprobar que existe el directorio `/home/obiwan/group`.
* Comprobar que existe el directorio `/home/obiwan/public`.
* Comprobar `/home/obiwan/private` tiene los permisos `700`.
* Comprobar `/home/obiwan/group` tiene los permisos `750`.
* Comprobar `/home/obiwan/public` tiene los permisos `755`.

## 5.2 Comprobar

* Comprobar la **sintaxis**.
* `teuton towerXX/test5`, **ejecutar** el test.
* Tenemos los **resultados** en el directorio `var/test5`. Comprobar que los resultados son los correctos.

# 6. Test: Otros sistemas

## 6.1 Crear el test

Partimos del ejemplo anterior:
* Copiar el test anterior y modificar los comandos de comprobación para adaptarlo a otro sistema operativo. En nuestro caso elegiremos SO Windows. Por ejemplo, para comprobar si existe un usuario cambiar `id nombre-alumno` por `net user alumno`, etc.

Fichero de configuración:
* Modificar `config.yaml` para monitorizar únicamente a la máquina Windows.
Esto es, usar el parámetro `:tt_skip: true` o `:tt_skip: false` según convenga para habilitar/deshabilitar el test en determinados "cases" o máquinas.

Definir las comprobaciones necesarias en start.rb para:
* Comprobar que existe el grupo jedis (`net localgroup`).
* Comprobar que existe el usuario obiwan (`net user`).
* Comprobar que obiwanes miembro del grupo jedis (`net user obiwan`).
* Comprobar que existe el directorio `C:\Users\obiwan\private`.
* Comprobar que existe el directorio `C:\Users\obiwan\group`.
* Comprobar que existe el directorio `C:\Users\obiwan\public`.

## 6.2 Comprobar

* Comprobar la **sintaxis**.
* `teuton towerXX/test6`, **ejecutar** el test.
* Tenemos los **resultados** en el directorio `var/test6`. Comprobar que los resultados son los correctos.
