<!--M007 - YA - Export activity to excel-->
<?php
$now = date('d-m-Y');
$jam = date('H.i.s');
$namafile = "Activity__Tanggal(".$now.")__Jam(".$jam.").xls";
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
 <Worksheet ss:Name="Activity">
 <Table border="1">
  <tr height="40">
    	<td id="judul"><Data ss:Type="String">Activity</Data></td>
     </tr>
     <tr height="20">
     	<td id="kepala" style="background-color:#F90"><Data ss:Type="String">Activity Code</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Activity Parent</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Parent Code</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Activity Description</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Activity Definition View</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Activity Level</Data></td>
        <td id="kepala" style="background-color:#F90"><Data ss:Type="String">Status Active</Data></td>
      </tr>
 <?php
 	$number = 0;
	foreach($hasil as $p)
	{
	$number = $number + 1;
 ?>
 	<tr>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->activity_code; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->activity_parent; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->parent_code; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->activity_description; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->activity_definition; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->activity_level; ?></Data></td>
    <td id="badan" align="right"><Data ss:Type="String"><?php echo $p->status_active; ?></Data></td>
    </tr>
 <?php
	}
 ?>
  </Table>
 </Worksheet>
</Workbook>