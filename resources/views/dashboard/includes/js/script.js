Pacientes = {}

$( "#search_paciente" ).autocomplete({
    source: function(request, response){
        $.ajax({
            url: "http://localhost/cmedico-mvc/pacienteslist",
            type: 'GET',
            dataType: 'json',
            success:function(data){
				Pacientes = $.map(data, function(value, key){
					return {
						id:value.codigo_paciente, 
						label:value.nome_paciente,    
						estado_civil:value.estado_civil, 
						genero:value.genero, 
						provincia:value.provincia, 
						distrito:value.distrito,
						bairro:value.bairro, 
						rua:value.rua,  
						telefone:value.telefone,  
						email:value.email,  
						data_nascimento:value.data_nascimento,
						id_chat:value.id_chat                
					};
				});
				var results = $.ui.autocomplete.filter(Pacientes, request.term);
				response(results);
            }
        });
    },
	select: function(event, ui){
		const date = new Date();
		var anoActual = date.getFullYear();
		let anoPaciente = ui.item.data_nascimento;
		let anoNasc = anoPaciente.substr(0, 4);
		let idade = anoActual - anoNasc;
		console.log(idade);
		$('#id_paciente').val(ui.item.id);
		$('#nome_paciente').val(ui.item.label);
		$('#data_paciente').val(idade);
		$('#email_paciente').val(ui.item.email);
		$('#telefone_paciente').val(ui.item.telefone);
		$('#genero').val(ui.item.genero);
		$('#provincia').val(ui.item.provincia);
		$('#distrito').val(ui.item.distrito);
		$('#bairro').val(ui.item.bairro);
		$('#estado').val(ui.item.estado_civil);
		$('#id_chat').val(ui.item.id_chat);
	}
});


// SIDEBAR DROPDOWN
const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');
const sidebar = document.getElementById('sidebar');

allDropdown.forEach(item=> {
	const a = item.parentElement.querySelector('a:first-child');
	a.addEventListener('click', function (e) {
		e.preventDefault();

		if(!this.classList.contains('active')) {
			allDropdown.forEach(i=> {
				const aLink = i.parentElement.querySelector('a:first-child');

				aLink.classList.remove('active');
				i.classList.remove('show');
			})
		}

		this.classList.toggle('active');
		item.classList.toggle('show');
	})
})





// SIDEBAR COLLAPSE
const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

if(sidebar.classList.contains('hide')) {
	allSideDivider.forEach(item=> {
		item.textContent = '-'
	})
	allDropdown.forEach(item=> {
		const a = item.parentElement.querySelector('a:first-child');
		a.classList.remove('active');
		item.classList.remove('show');
	})
} else {
	allSideDivider.forEach(item=> {
		item.textContent = item.dataset.text;
	})
}

toggleSidebar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');

	if(sidebar.classList.contains('hide')) {
		allSideDivider.forEach(item=> {
			item.textContent = '-'
		})

		allDropdown.forEach(item=> {
			const a = item.parentElement.querySelector('a:first-child');
			a.classList.remove('active');
			item.classList.remove('show');
		})
	} else {
		allSideDivider.forEach(item=> {
			item.textContent = item.dataset.text;
		})
	}
})




sidebar.addEventListener('mouseleave', function () {
	if(this.classList.contains('hide')) {
		allDropdown.forEach(item=> {
			const a = item.parentElement.querySelector('a:first-child');
			a.classList.remove('active');
			item.classList.remove('show');
		})
		allSideDivider.forEach(item=> {
			item.textContent = '-'
		})
	}
})



sidebar.addEventListener('mouseenter', function () {
	if(this.classList.contains('hide')) {
		allDropdown.forEach(item=> {
			const a = item.parentElement.querySelector('a:first-child');
			a.classList.remove('active');
			item.classList.remove('show');
		})
		allSideDivider.forEach(item=> {
			item.textContent = item.dataset.text;
		})
	}
})




// PROFILE DROPDOWN
const profile = document.querySelector('nav .profile');
const imgProfile = profile.querySelector('img');
const dropdownProfile = profile.querySelector('.profile-link');

imgProfile.addEventListener('click', function () {
	dropdownProfile.classList.toggle('show');
})




// MENU
const allMenu = document.querySelectorAll('main .content-data .head .menu');

allMenu.forEach(item=> {
	const icon = item.querySelector('.icon');
	const menuLink = item.querySelector('.menu-link');

	icon.addEventListener('click', function () {
		menuLink.classList.toggle('show');
	})
})



window.addEventListener('click', function (e) {
	if(e.target !== imgProfile) {
		if(e.target !== dropdownProfile) {
			if(dropdownProfile.classList.contains('show')) {
				dropdownProfile.classList.remove('show');
			}
		}
	}

	allMenu.forEach(item=> {
		const icon = item.querySelector('.icon');
		const menuLink = item.querySelector('.menu-link');

		if(e.target !== icon) {
			if(e.target !== menuLink) {
				if (menuLink.classList.contains('show')) {
					menuLink.classList.remove('show')
				}
			}
		}
	})
})





// PROGRESSBAR
const allProgress = document.querySelectorAll('main .card .progress');

allProgress.forEach(item=> {
	item.style.setProperty('--value', item.dataset.value)
})

function openConfigs(evt, configName) {
	var i, x, activeLinks;
	x = document.getElementsByClassName("navtab__config");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	activeLinks = document.getElementsByClassName("navtab__button");
	for (i = 0; i < x.length; i++) {
		activeLinks[i].className = activeLinks[i].className.replace(" borda", "");
	}
	document.getElementById(configName).style.display = "block";
	evt.currentTarget.className += " borda";
}

//=============================================
//funcao que muda o status do utilizador
//=============================================
function changeStatus(id) {
    var id_user = id;

    $.ajax({
        url: '?a=utilizadores',
        type: 'POST',
        data: { userId: id_user },
        success: function(result) {
            if (result == 'activo') {
                swal("Sucesso!", "Utilizador habilitado", "success");
            }
        }
    });
}