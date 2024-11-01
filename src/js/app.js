let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
	id: "",
	nombre: "",
	fecha: "",
	hora: "",
	servicios: [],
};

document.addEventListener("DOMContentLoaded", function () {
	iniciarApp();
});

function iniciarApp() {
	mostrarSeccion(); // MUESTRA Y OCULTA LA SECCION
	tabs(); // CAMBIA CUANDO SE SELECCIONE LOS TABS
	botonPaginador(); // MUESTRA U OCULTA EL BOTON DEL PAGINADOR
	paginaAnterior();
	paginaSiguiente();

	consultarAPI(); // CONSULTA LA API EN EL BACKEND EN EL PHP

	idCliente();
	nombreCliente(); // AÑADE NOMBRE AL OBJETO DE CITA
	fechaCita(); // AÑADE FECHA AL OBJETO DE CITA
	horaCita(); // AÑADE HORA AL OBJETO DE CITA

	mostrarResumen(); // MUESTRA RESUMEN DE LA CITA
}

function mostrarSeccion() {
	// OCULTA LA SECCION ANTERIOR
	const seccionAnterior = document.querySelector(".mostrar");
	if (seccionAnterior) {
		seccionAnterior.classList.remove("mostrar");
	}

	// SELECCIONAR SECCION SEGUN EL PASO
	const pasoSelect = `#paso-${paso}`;
	const seccion = document.querySelector(pasoSelect);
	seccion.classList.add("mostrar");

	// OCULTA EL RESALTAJE DEL TAB ANTERIOR
	const tabAnterior = document.querySelector(".actual");
	if (tabAnterior) {
		tabAnterior.classList.remove("actual");
	}

	// RESALTA EL TAB ACTUAL
	const tabActual = document.querySelector(`[data-paso="${paso}"]`);
	tabActual.classList.add("actual");
}

function tabs() {
	const botones = document.querySelectorAll(".tabs button");

	botones.forEach(function (boton) {
		boton.addEventListener("click", function (e) {
			paso = parseInt(e.target.dataset.paso);
			mostrarSeccion();

			botonPaginador();
		});
	});
}

function botonPaginador() {
	const paginaAnterior = document.querySelector("#anterior");
	const paginaSiguiente = document.querySelector("#siguiente");

	if (paso === 1) {
		paginaAnterior.classList.add("ocultar");
		paginaSiguiente.classList.remove("ocultar");
	} else if (paso === 3) {
		paginaSiguiente.classList.add("ocultar");
		paginaAnterior.classList.remove("ocultar");
		mostrarResumen();
	} else {
		paginaAnterior.classList.remove("ocultar");
		paginaSiguiente.classList.remove("ocultar");
	}
	mostrarSeccion();
}

function paginaAnterior() {
	const paginaAnterior = document.querySelector("#anterior");
	paginaAnterior.addEventListener("click", function () {
		if (paso <= pasoInicial) return;
		paso--;

		botonPaginador();
	});
}

function paginaSiguiente() {
	const paginaSiguiente = document.querySelector("#siguiente");
	paginaSiguiente.addEventListener("click", function () {
		if (paso >= pasoFinal) return;
		paso++;

		botonPaginador();
	});
}

async function consultarAPI() {
	try {
		const url = "/api/servicios";
		const resultado = await fetch(url);
		const servicios = await resultado.json();
		mostrarServicios(servicios);
	} catch (error) {
		console.log(error);
	}
}

function mostrarServicios(servicios) {
	servicios.forEach((servicio) => {
		const { id, nombre, precio } = servicio;

		const nombreServicio = document.createElement("P");
		nombreServicio.classList.add("nombre-servicios");
		nombreServicio.textContent = nombre;

		const precioServicio = document.createElement("P");
		precioServicio.classList.add("precio-servicios");
		precioServicio.textContent = `$${precio}`;

		const servicioDiv = document.createElement("DIV");
		servicioDiv.classList.add("servicio");
		servicioDiv.dataset.idServicio = id;
		servicioDiv.onclick = function () {
			seleccionarServicio(servicio);
		};
		servicioDiv.appendChild(nombreServicio);
		servicioDiv.appendChild(precioServicio);

		document.querySelector("#servicios").appendChild(servicioDiv);
	});
}

function seleccionarServicio(servicio) {
	const { id } = servicio;
	const { servicios } = cita;

	// IDENTIFICAR AL ELEMENTO QUE SE LE DA CLICK
	const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

	// COMPROBAR SI UN SERVICIO YA FUE AGREGADO
	if (servicios.some((agregado) => agregado.id === id)) {
		// ELIMINAR
		cita.servicios = servicios.filter((agregado) => agregado.id !== id);
		divServicio.classList.remove("seleccionado");
	} else {
		// AGREGARLO
		cita.servicios = [...servicios, servicio];
		divServicio.classList.add("seleccionado");
	}
}

function idCliente() {
	cita.id = document.querySelector("#id").value;
}
function nombreCliente() {
	cita.nombre = document.querySelector("#nombre").value;
}
function fechaCita() {
	const inputFecha = document.querySelector("#fecha");
	inputFecha.addEventListener("input", function (e) {
		const dia = new Date(e.target.value).getUTCDay();

		if ([6, 0].includes(dia)) {
			e.target.value = "";
			mostrarAlerta("La fecha seleccionada no es un día hábil", "error", ".formulario");
		} else {
			cita.fecha = e.target.value;
		}
	});
}

function horaCita() {
	const inputHora = document.querySelector("#hora");
	inputHora.addEventListener("input", function (e) {
		const horaCita = e.target.value;
		const hora = horaCita.split(":")[0];
		if (hora < 10 || hora > 18) {
			e.target.value = "";
			mostrarAlerta("La hora seleccionada no es válida", "error", ".formulario");
		} else {
			cita.hora = e.target.value;
		}
	});
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
	// PREVIENE QUE SE GENERE MULTIPLES ALERTAS
	const alertaPrevia = document.querySelector(".alerta");
	if (alertaPrevia) {
		alertaPrevia.remove();
	}

	// SCRIPTING - CREA LA ALERTA CON EL MENSAJE
	const alerta = document.createElement("DIV");
	alerta.textContent = mensaje;
	alerta.classList.add("alerta");
	alerta.classList.add(tipo);

	const referencia = document.querySelector(elemento);
	referencia.appendChild(alerta);

	// ELIMINA LA ALERTA DESPUÉS DE 3 SEGUNDOS
	if (desaparece) {
		setTimeout(() => {
			alerta.remove();
		}, 3000);
	}
}

function mostrarResumen() {
	const resumen = document.querySelector(".contenido-resumen");

	// LIMPIAR EL CONTENIDO DE RESUMEN
	while (resumen.firstChild) {
		resumen.removeChild(resumen.firstChild);
	}

	if (Object.values(cita).includes("") || cita.servicios.length === 0) {
		mostrarAlerta("Faltan seleccionar datos o servicios", "error", ".contenido-resumen");

		return;
	}

	// FORMATEAR EL DIV DE RESUMEN
	const { nombre, fecha, hora, servicios } = cita;

	// HEADING PARA EL RESUMEN DE SERVICIOS
	const headingServicio = document.createElement("H3");
	headingServicio.innerHTML = "Resumen de servicios";
	resumen.appendChild(headingServicio);

	// ITERAR LOS SERVICIOS EN EL DIV DE RESUMEN
	servicios.forEach((servicio) => {
		const { id, nombre, precio } = servicio;
		const contenedorServicio = document.createElement("DIV");
		contenedorServicio.classList.add("contenido-servicio");

		const textoServicio = document.createElement("P");
		textoServicio.textContent = nombre;

		const precioServicio = document.createElement("P");
		precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

		contenedorServicio.appendChild(textoServicio);
		contenedorServicio.appendChild(precioServicio);

		resumen.appendChild(contenedorServicio);
	});

	// HEADING PARA EL RESUMEN DE CITA
	const headingCita = document.createElement("H3");
	headingCita.innerHTML = "Resumen de cita";
	resumen.appendChild(headingCita);

	const nombreCliente = document.createElement("P");
	nombreCliente.innerHTML = `<span>Nombre del cliente:</span> ${nombre}`;

	// FORMATEAR LA FECHA EN ESPAÑOL
	const fechaObj = new Date(fecha);
	const year = fechaObj.getFullYear();
	const mes = fechaObj.getMonth();
	const dia = fechaObj.getDate() + 2;

	const fechaUTC = new Date(Date.UTC(year, mes, dia));

	const opciones = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
	const fechaFormateada = fechaUTC.toLocaleDateString("es-AR", opciones);

	const fechaCita = document.createElement("P");
	fechaCita.innerHTML = `<span>Fecha de cita:</span> ${fechaFormateada}`;

	const horaCita = document.createElement("P");
	horaCita.innerHTML = `<span>Hora de cita:</span> ${hora}`;

	// BOTON PARA RESERVAR CITA
	const botonReservar = document.createElement("BUTTON");
	botonReservar.classList.add("boton");
	botonReservar.textContent = "Reservar Cita";
	botonReservar.onclick = reservarCita;

	resumen.appendChild(nombreCliente);
	resumen.appendChild(fechaCita);
	resumen.appendChild(horaCita);
	resumen.appendChild(botonReservar);

	console.log(cita);
}

async function reservarCita() {
	const { nombre, fecha, hora, servicios, id } = cita;
	const idServicios = servicios.map((servicio) => servicio.id);
	//console.log(idServicios);

	const datos = new FormData();
	datos.append("fecha", fecha);
	datos.append("hora", hora);
	datos.append("usuarioId", id);
	datos.append("servicios", idServicios);

	// console.log([...datos]);

	try {
		// PETICION A LA API
		const url = "/api/citas";
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});

		const resultado = await respuesta.json();

		if (resultado.resultado) {
			Swal.fire({
				icon: "success",
				title: "Cita Creada",
				text: "La Cita fue creada exitosamente",
				button: "OK",
			}).then(() => {
				window.location.reload();
			});
		}
	} catch (error) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Hubo un error al crear la cita",
		});
	}

	// console.log([...datos]);
}
