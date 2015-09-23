<!--[if !IE]>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="name filename"><span>{%=file.name%}</span></td>
        {% if (file.error) { %}
            <td class="error">{%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td class="progress-col">
 					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
						<div class="bar" style="width:0%;"></div>
					</div>
            </td>
        {% } else { %}
            <td> </td>
        {% } %}
		<td class="cancel">{% if (file.error) { %}
            <button class="btn">
                <i class="cancel"></i>
			</button>
		{% } else { %}
			<button class="btn">
                <i class="cancel"></i>
			</button>
			<span class="uploading"></span>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!--<![endif]-->

<!--[if IE]>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="name filename"><span>{%=file.name%}</span></td>
        {% if (file.error) { %}
            <td class="error">{%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td class="progress-col">
 					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
						<div class="bar-ie" style="width:0%;"></div>
					</div>
            </td>
        {% } else { %}
            <td> </td>
        {% } %}
		<td class="cancel">{% if (file.error) { %}
            <button class="btn">
                <i class="cancel"></i>
            </button>
		{% } else { %}
			<button class="btn">
                <i class="cancel"></i>
			</button>
			<span class="uploading"></span>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!--<![endif]-->