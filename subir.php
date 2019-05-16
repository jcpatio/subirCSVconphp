<?php
date_default_timezone_set( 'America/Argentina/Tucuman' );
include( "tu archivo de conexion" );
$con = conectar();///funcion en mi caso es  function conectar(){}

$numero= rand(1, 10000000); ////genera un numero random
$idUser = $_POST[ 'idUser' ];
$dir_subida = 'la carpeta donde se van a guardar';
$nombreFichero = $numero.basename( $_FILES[ 'fichero_usuario' ][ 'name' ] );
$fichero_subido = $dir_subida . $nombreFichero;

$fechar = date( "Y-m-d H:i:s" );


	
if ( move_uploaded_file( $_FILES[ 'fichero_usuario' ][ 'tmp_name' ], $fichero_subido ) ) {

///------------------------------------------------------------------------------------------------////			
			//insetamos el nombre del fichero a la base de datos
			$queryFicheroSubido = "INSERT INTO ficheros_subidos_poductos (fichero_subido, fechar) VALUES('$nombreFichero', '$fechar')";
			mysqli_query( $con, $queryFicheroSubido )or die( mysqli_error() );

			$ficheroTXTid = mysqli_insert_id( $con );
	

//////------------------------------------------------------------------------------------------------/////		
        
  
    $fichero = fopen($fichero_subido,"r")or die("No se consigue el archivo");
    
        while (!feof($fichero)){
        $campo = fgetcsv($fichero,4096,";");                      
        $descripcion= utf8_encode($campo[1]);
                        
        $qProductos = "select * from productos where codigo ='$campo[0]'";
        $rProductos = mysqli_query( $con, $qProductos)or die( mysqli_error() );
        $existe = mysqli_num_rows( $rProductos );

        if($existe>0){
            
            //insetamos la actualizacion del usuario
            $qActualizacionProducto = "UPDATE productos SET  descripcion='$descripcion', precio='$campo[2]'  WHERE codigo='$campo[0]'";
            mysqli_query($con,$qActualizacionProducto) or die(mysqli_error());
            
        }            
        else{
        
           //insetamos el producto nuevo
            $qProductoNuevo= "INSERT INTO productos (codigo,descripcion, precio) VALUES('$campo[0]','$descripcion', '$campo[2]')";
            mysqli_query( $con, $qProductoNuevo )or die( mysqli_error() );
              }
                
         }
       fclose($fichero);
    
    
	}
echo "<script language='Javascript'> window.location='webREdireccionar.php?msg=3';
	  </script>";
?>
