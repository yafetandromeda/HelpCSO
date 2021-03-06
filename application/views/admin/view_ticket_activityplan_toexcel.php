<!--M009 - YA - Export ticket activityplan to excel-->
<?php
$now = date('d-m-Y');
$jam = date('H.i.s');
$namafile = "Activityplan_".$ticket_template_id."_Tanggal(".$now.")__Jam(".$jam.").xls";
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
   <ss:Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
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
 <Worksheet ss:Name="ActivityPlan">
 <Table border="1">
  <tr height="40">
    	<td id="judul"><Data ss:Type="String">Activity Plan</Data></td>
     </tr>
     <tr height="20">
     	<td id="kepala" style="background-color:#F90"><Data ss:Type="String">Plan ID</Data></td>
     	<td id="kepala" style="background-color:#F90"><Data ss:Type="String">Plan Order</Data></td>
      <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Action Name</Data></td>
      <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Function Name</Data></td>
      <td id="kepala" style="background-color:#F90"><Data ss:Type="String">SLA</Data></td>
      <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Status Active</Data></td>
      <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Ticket Template ID</Data></td>
      </tr>
 <?php
 	$number = 0;
	foreach($hasil as $p)
	{
	$number = $number + 1;
 ?>
 	<tr>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->plan_id; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->plan_order; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->action_name; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->function_name; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->sla; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->status_active; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $ticket_template_id; ?></Data></td>
    </tr>
 <?php
	}
 ?>
  </Table>
 </Worksheet>
</Workbook>