<?php

	# conectare la base de datos
    $con=@mysqli_connect('localhost', 'root', '', 'diaro');
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		include 'pagination.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 4; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/_
		$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM estudiante");
		if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		$total_pages = ceil($numrows/$per_page);
		$reload = 'ingreso_estudiantes.php';
		//consulta principal para recuperar los datos
		$query = mysqli_query($con,"SELECT e.id_estudiante, e.identificacion, e.nombre_est, e.nombre2_est, e.apellido_est, e.apellido2_est,c.nombre_curso,e.id_padresflia, p.nombre_1, p.apellido_1  FROM estudiante e
      inner join padresflia p  on e.id_padresflia=p.id_padres inner join cursos c on e.id_curso=c.id_cursos  LIMIT $offset,$per_page");
		
		if ($numrows>0){
			?>
			<link rel="stylesheet" href="css/jquery.dataTables.min.css" >
			<script src="js/jquery.dataTables.min.js" ></script>
			<div class="table-responsive">
            <table class="table table-striped table-hover table-condensed">
            <thead class="thead">
                <tr style="background-color: #f5f8fa;">
                  
                        <th style=" text-align:left;">Id</th>
                        <th style=" text-align:left;">Identificación</th>
                        <th style=" text-align:left;">Primer nombre</th>
                        <th style=" text-align:left;">Segundo nombre</th>
                        <th style=" text-align:left;">Primer apellido</th>
                        <th style=" text-align:left;">Segundo apellido</th>
                        <th style=" text-align:left;">Curso</th>
                        <th style=" text-align:left;">Nombre Acudiente</th>
                        <th style=" text-align:left;">Apellido Acudiente</th>
                         <th style=" text-align:center;"><em class="glyphicon glyphicon-cog"></em></th>
                </tr>
			</thead>
			<tbody>
			<?php
			while($row = mysqli_fetch_array($query)){
				?>
					 <tr>
                  			 	
                  			 	<td align="left"><b><?php echo $row['id_estudiante']; ?></b></td>
                  			 	<td align="left"><?php echo $row['identificacion']; ?></td>
                  			 	<td align="left"><?php echo $row['nombre_est']; ?></td>
                  			 	<td align="left"><?php echo $row['nombre2_est']; ?></td>
                  			 	<td align="left"><?php echo $row['apellido_est']; ?></td>
                  			 	<td align="left"><?php echo $row['apellido2_est']; ?></td>
                          <td align="left"><?php echo $row['nombre_curso']; ?></td>
                  			 	<td align="left"><?php echo $row['nombre_1']; ?></td>
                  			 	<td align="left"><?php echo $row['apellido_1']; ?></td>
                  			 	
                  			 	<td align="center">

            <a  data-toggle="modal" data-target="#dataUpdate"
                data-id_estudiante="<?php echo $row['id_estudiante']?>"
                data-identificacion="<?php echo $row['identificacion']?>" 
                data-nombre_est="<?php echo $row['nombre_est']?>"
                data-nombre2_est="<?php echo $row['nombre2_est']?>"
                data-apellido_est="<?php echo $row['apellido_est']?>"
                data-apellido2_est="<?php echo $row['apellido2_est']?>"
               data-nombre_curso="<?php echo $row['nombre_curso']?>"
                data-id_curso="<?php echo $row['id_curso']?>"
                data-id_padresflia="<?php echo $row['id_padresflia']?>" >
                          <span class="glyphicon glyphicon-edit" style="color:#00a59c"></span>
                          </a>&nbsp;&nbsp;


      <a href="#" data-toggle="modal" data-target="#dataDelete" data-id_estudiante="<?php echo $row['id_estudiante']?>">
                          <span class="glyphicon glyphicon-trash" style="color:#00a59c"></span>
                          </a>
                           
                          </td>
                  			 </tr>
				<?php
			}
			?>
			</tbody>
			
		</table>
			<div class="table-pagination pull-right">
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
			</div>
		
			<?php
			
		} else {
			?>
			<div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>
