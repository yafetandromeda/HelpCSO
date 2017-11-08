<?php
$now = date('d-m-Y');
$jam = date('H.i.s');
$namafile = "InteractionReport__Tanggal(".$now.")__Jam(".$jam.").xls";
header("Content-Type: application/xls");;
header("Content-Disposition: attachment;filename=".$namafile." ");
?>
<?php
	echo "<?phpxml version=\"1.0\"?>";
	echo "<?phpmso-application progid=\"Excel.Sheet\"?>";
?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="judul">
	<ss:Alignment ss:Vertical="Center"/>
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="20" ss:Color="#000000" ss:Bold="1"/>
  </Style>
  <Style ss:ID="kepala">   
	<ss:Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#FF0000" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="badan">
   <ss:Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="sumso">
   <ss:Alignment ss:Horizontal="Right" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Interaction Report">
 <Table border="1">
  <tr height="40">
    	<td id="judul"><Data ss:Type="String">Interaction Report</Data></td>
     </tr>
     <tr height="20">
     	<td id="kepala" style="background-color:#F90"><Data ss:Type="String">Number</Data></td>
     	<td id="kepala" style="background-color:#F90"><Data ss:Type="String">Interaction ID</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Interaction Type Name</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Customer Name</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Customer Phone</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Customer Email</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Queue Number</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Creator Name</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Status</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Create Datetime</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Planned Start Datetime</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Actual Start Datetime</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Actual End Datetime</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Actual Cancel Datetime</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Interaction Information</Data></td>
      </tr>
 <?php
 	$number = 0;
	foreach($hasil as $p)
	{
	$number = $number + 1;
 ?>
 	<tr>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $number; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->code_interaction; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->interaction_type_name; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->customer_name; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->customer_phone; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->customer_email; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->queue_number; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->creator_name; ?></Data></td>  
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->status_name; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo ($p->creator_datetime ? date("Y-m-d H:i:s", strtotime($p->creator_datetime)) : ''); ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo ($p->planned_start_datetime ? date("Y-m-d H:i:s", strtotime($p->planned_start_datetime)) : ''); ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo ($p->actual_start_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_start_datetime)) : ''); ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo ($p->actual_end_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_end_datetime)) : ''); ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo ($p->actual_cancel_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_cancel_datetime)) : ''); ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo preg_replace('/<[^>]*>/', '', $p->interaction_description); ?></Data></td>
    </tr>
 <?php
	}
 ?>
  </Table>
 </Worksheet>
</Workbook>

