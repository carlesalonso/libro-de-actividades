
```
Curso           : 202223, 202021, 201920
Area            : Sistemas Operativos, almacenamiento, volúmenes
Descripción     : Gestión de volúmenes lógicos con varios SSOO.
Requisitos      : SO OpenSUSE (Yast) y Windows
Tiempo estimado : 6 sesiones
```

---
# Volúmenes Lógicos (OpenSUSE + Windows)

* Leer el siguiente enlace ([LVM para torpes](https://blog.inittab.org/administracion-sistemas/lvm-para-torpes-i/)) para entender qué son los volúmenes lógicos.
* OPCIONAL: [SUSE - LVM documentation](https://documentation.suse.com/sles/15-SP1/html/SLES-all/cha-lvm.html)
* OPCIONAL: [Red Hat - Manual LVM](https://access.redhat.com/documentation/es-es/red_hat_enterprise_linux/5/html-single/logical_volume_manager_administration/index)

# 1. Instalar SO sobre LVM

Vamos a instalar un sistema operativo GNU/Linux OpenSUSE desde cero, sobre volúmenes(LVM) en lugar de particiones.

## 1.1 Preparar MV

Realizar las siguientes tareas:
* Crear MV para OpenSUSE, con lo siguiente:

| Opción EFI-BIOS   | Opción BIOS legacy |
| ----------------- | -------- |
| Un disco de 10 GB | Un disco de 10 GB |
| Sistema UEFI-BIOS activo | Sin EFI |
| 2 interfaces de red | 2 interfaces de red |
| [Configuración](../../global/configuracion/opensuse.md) | [Configuración](../../global/configuracion/opensuse.md) |

* Comenzar a instalar el sistema operativo.
* ¿Activar repositorios en línea? NO
* Escritorio: XFCE
* OJO: Parar al llegar al particionado.

## 1.2 Creando los volúmenes físicos (particiones y discos)

> Vamos a crear una partición para el **sistema de arranque** independiente. De modo que se quedará fuera de los volúmenes (LVM).

| OPCION      | Partición | Size    | Tipo de partición       | Formato | Punto de montaje |
| ----------- | ---- | ------- | ----------------------- | ------- | ---------------- |
| **EFI-BIOS**    | 1 | 300 MiB | Sistema de arranque EFI | FAT     | /boot/efi        |
|             | 2 | Resto   | Volúmen sin procesar    | (Linux LVM) | - |
| **BIOS legacy** | 1 | 8 MiB | Arranque BIOS       | FAT4    | - |
|             | 2 | 300 MiB | Sistema operativo       | ext4    | /boot            |
|             | 3 | Resto   | Volúmen sin procesar    | (Linux LVM) | - |

![](images/opensuse-lvm-volumenes-fisicos.png)

## 1.3 Creando los volúmenes lógicos (grupos lógicos y volúmenes lógicos)

* Ir a la gestión de volúmenes (LVM).
* Crear un grupo de volumen llamado `grupoXX` que coja el disco disponible. Donde XX es el número asociado a cada alumno.

![](images/opensuse-lvm-grupo-logico.png)

* Crear los siguientes volúmenes lógicos (LV) dentro del `grupoXX`:

| Nombre    | Tamaño  | Tipo                | Formato | Montar |
| --------- | ------- | ------------------- | ------- | ------ |
| volXXswap | 500 MiB | Area de intercambio | swap    | -      |
| volXXssoo | 8 GiB   | Sistema operativo   | ext4    | /      |
| volXXhome | 100 MiB | Datos de usuario    | xfs     | /home  |

![](images/opensuse-lvm-volumenes-logicos.png)

Vemos que nos ha sobrado espacio. Lo dejamos así porque lo usaremos más adelante.

Por la experiencia de instalaciones previas de OpenSUSE con entorno gráfico, ya sabemos que el espacio en disco se nos va a quedar pequeño enseguida. Cuando se nos llene el espacio del sistema, vamos a hacer uso de los volúmenes (LVM) para ampliarlo sin necesidad de reinstalar el sistema.

* Seguimos con la instalación del sistema operativo.
* En configuración de la instalación:
    * Habilitar servicio SSH y Abrir puerto SSH.
* Reiniciamos el sistema y seguimos.

> ¿Te acordaste de quitar la ISO (DVD) de la unidad de la MV?

Si no puedes entrar al sistema o no se inicia correctamente...
1. Repasa bien los pasos anteriores y volver a hacer.
2. Si sigues con problemas, llama a alguien que se siente al lado y le vas contando en voz alta los pasos mientras lo vuelves a repetir para que te des cuenta de dónde has tenido el fallo.
3. ¡Uff!... espero que no lleguemos a este punto...

## 1.4 Comprobación de la instalación con volúmenes (LVM)

Podemos hacernos la idea de que:
* los "grupos de volumen" son como si fueran discos, y
* los "volúmenes lógicos" particiones.

Configuramos la primera tarjeta de red en modo estático/manual:
* Ir a `Yast -> Red`.
* Cambiar gestor de red "Network Manager" por "Wicked".
* Configurar IP, máscara, puerta de enlace y servidor DNS.

Comandos de comprobación:
```
date
hostname
ip a
lsblk              # Muestra discos, particiones, volúmenes y puntos de montaje
vgdisplay          # Muestra los grupos de volumen
```

| Regla   | Descripción |
| ------- | ----------- |
| **nº1** | _Si no sabes o no entiendes para qué sirven estos comandos... no sigas. Averigua, lee, prueba y pregunta_ |
| **nº2** | _Primero aprende la regla nº 1_ |

---
# 2 Aumentar el tamaño del VL en caliente

Ahora podremos ampliar _"en caliente"_, el espacio de `volXXhome` de 100MB a 400MB.

## 2.1 Ampliamos

* Consultar el tamaño actual del volumen lógico: `lvdisplay -v /dev/grupoXX/volXXhome`
* Podemos usar `Yast` para ampliar el tamaño del volumen lógico. O también podemos hacerlo por comandos: `lvextend --resizefs -L 400 /dev/grupoXX/volXXhome`.
* Comprobar con: `lvdisplay -v /dev/grupoXX/volXXhome`

## 2.2 Comprobamos

* Comprobamos lo que tenemos por ahora:
```
lsblk
vgdisplay          # Comprobar que ha aumentado el espacio ocupado
lvdisplay grupoXX
df -hT
```

> **¿Qué pasa si la salida del comando es muy larga?**
>
> * Si la salida de algún comando es muy extensa y es difícil hacer las capturas de pantalla, podemos hacer lo siguiente. `lvdisplay grupoXX > salida.txt`. Esto es "redirección de comandos". El primer comando pasa toda la salida de pantalla a un fichero de texto con el nombre `salida.txt`. Luego más tarde, podemos incluir dicho texto en nuestro informe.
> * Otra forma es usando el comando `script`. Este comando cuando se lanza queda activo y todos los comandos y sus salidas quedan registradas en un fichero de texto (`typescript`). Al terminar hacemos `exit`.

> **Coherencia con los datos obtenidos de distintos comandos**
> * Si el comando `df -hT` no nos devuelve el tamaño que esperamos para el volumen, entonces podemos reiniciar la MV y ver si se ha actualizado la información, o usar `resize2fs /dev/grupoXX/volXXhome` para ajustar dicho valor.

---
# 3. Modificar el espacio físico LVM

* Hacer una instantánea o copia de seguridad de la MV antes de seguir.

Vamos a añadir a la máquina anterior, más almacenamiento físico LVM, puesto que ya hemos agotado todo el espacio libre de los discos físicos.

Esquema de PV, VG y LV:

![lvm-esquema1](./images/lvm-esquema1.jpeg)

## 3.1 Preparar la MV

* Añadimos 2 discos virtuales nuevos a la MV:
    * Disco de 200MB (B)
    * Disco de 750MB (C). Crear 3 particiones de 250 MB para usar en los volúmenes.
    Sin formato, ni tipo.

> NOTA: Las particiones las podemos crear con fdisk, gparted, Yast, etc.
>
> * Para crear particiones del disco sdb con fdisk:
>     * `fdisk /dev/sdb`
>     * `m` ver el menú con las opciones
>     * `n` para crear partición -> `p` primaria -> Número de la partición
>     * `w` grabar y salir
>     * `q` salir sin grabar

## 3.2 Crear nuevo grupo y volumen

* Crear la carpeta `/mnt/folderXXextra`. Más adelante la usaremos como punto de montaje.
* Ir a Yast -> Particionador.
* Crear un Grupo de Volumen llamado `grupoXXextra`, con el disco (B) y las 2 primeras particiones del (C).
* Crear un nuevo Volumen Lógico
    * Nombre: `volXXextra`
    * Tamaño: 690MB.
    * Formato: `ext4`
    * Punto de montaje: `/mnt/folderXXextra`.

> NOTA: La partición 3 del disco (C) NO la estamos usando por ahora.

![](images/lvm-esquema-grupos.png)

* Comprobamos lo que tenemos:
```
vgdisplay grupoXXextra # Información del grupo "extra"
lvdisplay grupoXXextra # Volúmenes lógicos del grupo "extra"
df -hT                 # Volumen montado
lsblk
```

> **ATENCION:**
> * Si la salida de algún comando es muy extensa y es difícil hacer las capturas de pantalla, podemos hacer lo siguiente. `lvdisplay grupoXXextra > salida.txt`, este comando pasa toda la salida de pantalla a un fichero de texto con el nombre `salida.txt`. Luego más tarde, podemos incluir dicho texto en nuestro informe.

## 3.3 Escribir información

A partir de ahora todo lo que escribamos en la carpeta `/mnt/folderXXextra` se estará guardando en el dispositivo montado.

* Comprobar el espacio usado en `/mnt/folderXXextra` (df -hT).
* Escribir información en `/mnt/folderXXextra`. Crear algunas carpetas y ficheros con tamaño mayor a cero.

> Por ejemplo para crear un archivo de tamaño 1M podemos hacer `dd if=/dev/zero of=/mnt/folderXXextra/file-size-1M bs=512 count=2048`.
> **OJO**: El comando dd hay que usarlo con precaución.

* Comprobar el espacio usado en `/mnt/folderXXextra` (df -hT).

## 3.4 Añadir más espacio

**Ampliar el tamaño del grupo extra**
* Añadir la tercera partición del disco (C) (no utilizada) al `grupoXXextra`. Podemos hacerlo por Yast.
Esto es, ir a `Yast -> Particionador -> grupoXXextra -> volúmenes físicos`. Añadir nuevo volumen físico.
* `vgdisplay grupoXXextra`, para comprobar la ampliación del tamaño del grupo.

**Ampliar el tamaño del volumen extra**

* Podemos usar `Yast` para ampliar el tamaño de volXXextra a 930MB o los comandos.
* En el caso de que automáticamente no nos aparezca la ampliación a 930MB usar el
siguiente comando: `lvextend -l +100%FREE /dev/grupoXXextra/volXXextra`.
* Comprobar el aumento del espacio (lvdisplay)
* Comprobar que los datos/información no se han borrado al ampliar el volumen lógico.

![](images/lvm-esquema-3.4.png)

## 3.5 Quitar un disco físico del grupo

* Hacer una instantánea o copia de seguridad de la MV antes de seguir.

> En LVM los discos físicos se llaman volúmenes físicos (Physical Volumes).

El grupo de volumen `grupoXXextra`, tiene como volúmenes físicos las particiones de los discos (B) y (C). En los pasos siguientes vamos a dejar de usar disco (C) dentro del grupo, sin perder la información almacenada en él. Y además en "caliente".

* Primero comprobamos el tamaño de nuestros datos: `du -sh /mnt/folderXXextra`. Este valor debe ser menor a 50 MB si queremos reducir el volumen.

* **Reducir el tamaño del volumen lógico** lvXXextra a 50 MB. Lo podemos hace por `Yast`. Si lo hacemos por comandos sería algo como `lvreduce --size 50MB /dev/grupoXXextra/volXXextra`.

>  `df -hT` debe mostrar el mismo tamaño que el que tiene el volumen ahora.

* Comprobamos: `lvdisplay /dev/grupoXXextra/volXXextra`.

Antes de quitar el disco hay que asegurarse de que no guarda datos.
* **Movemos la información** del disco sdc al disco sdb:

```
pvmove /dev/sdc1 /dev/sdb
pvmove /dev/sdc2 /dev/sdb
pvmove /dev/sdc3 /dev/sdb
```

* **Reducir el tamaño del grupo** de volumen:

```
vgreduce grupoXXextra /dev/sdc1
vgreduce grupoXXextra /dev/sdc2
vgreduce grupoXXextra /dev/sdc3
```

* Comprobar que se mantiene la información almacenada.

## 3.6 Comprobamos

* Comprobamos lo que tenemos:

```
lsblk
vgdisplay grupoXXextra
lvdisplay grupoXXextra
```
* Ahora podemos a quitar el disco `/dev/sdc` de la MV sin problemas.

---

# 4. Discos dinámicos en Windows

En Windows las particiones se llaman volúmenes básicos. Para poder hacer el mismo efecto que hicimos con LVM, primero debemos convertir las particiones a volúmenes dinámicos.

## 4.1 Volumen Distribuido

Un volumen Distribuido de Windows se parece a RAID0 pero NO es RAID0. Un volumen distribuido usa discos de distinto tamaño para crear otro mayor. Es el mismo efecto que el conseguido con LVM y los volúmenes lógicos.

* Vídeo sobre la [Creación de un volumen distribuido en Windows7](https://www.youtube.com/watch?v=prXBbHvqgx8)
* Añadimos 2 discos virtuales nuevos:
    * Disco de 200MB (B): con una partición completa del disco
    * Disco de 600MB (C): con 2 particiones de 300MB sin formato, ni tipo.
* Nota: los volúmenes simples del primer disco (A) deben permanecer intactos.
* Vamos a crear un volumen *Distribuido* con el disco B y la
primera partición del disco C.
* Comprobar que con la letra de unidad que hayamos escogido para el volumen, se accede a una zona de almacenamiento (volumen dinámico) formada por partes (particiones o volúmenes básicos) de varios discos diferentes.
