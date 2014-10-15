function btn_add_new_env()
{
	var i = 0;
	
	var id = "env_name_" + i;
	
	var last = document.getElementById(id);
	
	while(last)
	{
		
		i++;
		id = "env_name_" + i;
		last = document.getElementById(id);
	}
	
	create_env_form(i);
}


function btn_add_new_step()
{
	var i = 0;
	
	var id = "step_" + i;
	
	var last = document.getElementById(id);
	
	while(last)
	{
		
		i++;
		id = "step_" + i;
		last = document.getElementById(id);
	}
	
	create_step_form(i);
}

function delete_env(env_i)
{
	var env_id = document.getElementById('env_id_'+env_i);

	if(env_id)
	{
		var del_list = document.getElementById('delete_envs');
		attr = del_list.getAttribute('value');
		del_list.setAttribute('value', attr+env_id.getAttribute('value')+';');
	}
	
	var frm_grp = document.getElementById('env_'+env_i);
	var envs_div = document.getElementById('envs');
	envs_div.removeChild(frm_grp);
}

function delete_step(step_i)
{
	var step_id = document.getElementById('step_id_'+step_i);

	if(step_id)
	{
		var del_list = document.getElementById('delete_steps');
		attr = del_list.getAttribute('value');
		del_list.setAttribute('value', attr+step_id.getAttribute('value')+';');
	}
	
	var frm_grp = document.getElementById('step_'+step_i);
	var steps_div = document.getElementById('steps');
	steps_div.removeChild(frm_grp);
}

function create_step_form(i)
{
	var name = "step_" + i;
	var exe = "step_exe_" + i;
	var args = "step_args_" + i;
	var order = "step_order_" + i;

	var form_grp = document.createElement("div");
	form_grp.setAttribute('class', 'form-group');
	form_grp.setAttribute('id', name);
	
	var lbl = document.createElement('label');
	lbl.setAttribute('for', exe);
	lbl.innerHTML = 'Executable'
	
	form_grp.appendChild(lbl);
	
	var input = document.createElement('input');
	input.setAttribute('id', exe);
	input.setAttribute('name', exe);
	input.setAttribute('type', 'text');
	input.setAttribute('class', 'form-control');
	
	form_grp.appendChild(input);
	
	lbl = document.createElement('label');
	lbl.setAttribute('for', args);
	lbl.innerHTML = 'Arguments'
	
	form_grp.appendChild(lbl);
	
	input = document.createElement('input');
	input.setAttribute('id', args);
	input.setAttribute('name', args);
	input.setAttribute('type', 'text');
	input.setAttribute('class', 'form-control');
	
	form_grp.appendChild(input);
	
	lbl = document.createElement('label');
	lbl.setAttribute('for', order);
	lbl.innerHTML = 'Order'
	
	form_grp.appendChild(lbl);
	
	input = document.createElement('input');
	input.setAttribute('id', order);
	input.setAttribute('name', order);
	input.setAttribute('type', 'text');
	input.setAttribute('value', '0');
	input.setAttribute('class', 'form-control');
	
	form_grp.appendChild(input);
	
	btn = document.createElement('button');
	btn.setAttribute('type', 'button');
	btn.innerText = 'Delete'
	btn.setAttribute('class', 'btn btn-default');
	btn.setAttribute('onclick', "delete_step("+i+");");
	
	form_grp.appendChild(btn);
	
	var steps_div = document.getElementById('steps');
	var btn = document.getElementById('step_add_btn');
	steps_div.insertBefore(form_grp, btn);
}


function create_env_form(i)
{
	var name = "env_name_" + i;
	var val = "env_value_" + i;

	var form_grp = document.createElement("div");
	form_grp.setAttribute('class', 'form-group');
	form_grp.setAttribute('id', 'env_'+i);
	
	var lbl = document.createElement('label');
	lbl.setAttribute('for', name);
	lbl.innerHTML = 'Name'
	
	form_grp.appendChild(lbl);
	
	var input = document.createElement('input');
	input.setAttribute('id', name);
	input.setAttribute('name', name);
	input.setAttribute('type', 'text');
	input.setAttribute('class', 'form-control');
	
	form_grp.appendChild(input);
	
	lbl = document.createElement('label');
	lbl.setAttribute('for', val);
	lbl.innerHTML = 'Value'
	
	form_grp.appendChild(lbl);
	
	input = document.createElement('input');
	input.setAttribute('id', val);
	input.setAttribute('name', val);
	input.setAttribute('type', 'text');
	input.setAttribute('class', 'form-control');
	
	form_grp.appendChild(input);
	
	btn = document.createElement('button');
	btn.setAttribute('type', 'button');
	btn.innerText = 'Delete'
	btn.setAttribute('class', 'btn btn-default');
	btn.setAttribute('onclick', "delete_env("+i+");");
	
	form_grp.appendChild(btn);
	
	var envs_div = document.getElementById('envs');
	var btn = document.getElementById('env_add_btn');
	envs_div.insertBefore(form_grp, btn);
}
