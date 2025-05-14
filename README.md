# Gestión de Cursos/Eventos Académicos - UTA FISEI

## Descripción

Este proyecto corresponde al segundo parcial de la asignatura **Manejo y Configuración de Software** de la carrera de Software en la **Universidad Técnica de Ambato**. Consiste en el desarrollo de una aplicación web/escritorio para la **gestión de eventos académicos**, como cursos, congresos, webinars y conferencias, organizada por la Facultad de Ingeniería en Sistemas, Electrónica e Industrial (FISEI).

El sistema integra funcionalidades de administración de eventos, inscripción, control de pagos, generación de reportes y certificados, todo ello con **buenas prácticas de control de versiones y control de cambios**.

## Objetivo

Reforzar los conocimientos adquiridos sobre el proceso de **gestión de la configuración del software**, desarrollando colaborativamente una aplicación mediante herramientas como **Git**, **GitHub** y **Jira Service Management**, aplicando control de versiones y cambios.

## Funcionalidades Principales

- CRUD de cursos y eventos académicos
- Gestión de organizadores y participantes
- Roles múltiples: administrador, participante, organizador, etc.
- Inscripción a eventos con verificación de requisitos
- Gestión de pagos (depósito/transferencia) con carga de comprobantes
- Aprobación manual de comprobantes por parte de administradores
- Generación de:
  - Orden de pago
  - Reporte de asistencia
  - Reporte de notas (en caso de cursos)
  - Certificados de participación o aprobación

## Reglas del Negocio

- Existen distintos tipos de eventos: cursos, congresos, webinars, etc.
- Los eventos pueden ser gratuitos o pagados y estar dirigidos a ciertas carreras o al público en general.
- Los cursos incluyen número de horas, nota de aprobación y categoría.
- Todos los eventos registran asistencia, y los cursos adicionalmente registran una nota final.
- El sistema debe permitir búsquedas avanzadas de eventos.

## Flujo General del Sistema

1. **Visualización de eventos** con filtros avanzados.
2. **Inscripción** del usuario a un evento (verificación de requisitos y perfil).
3. **Generación de orden de pago** con opción de adjuntar comprobante.
4. **Aprobación del pago** por parte del administrador.
5. **Participación en el evento** y registro de asistencia/notas.
6. **Generación de certificados** y reportes finales.

## Herramientas Utilizadas

- Lenguaje: php, html y css
- Git & GitHub: control de versiones
- IDEs: Visual Studio Code
## Estructura del Repositorio

