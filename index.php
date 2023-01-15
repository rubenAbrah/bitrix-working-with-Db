<?  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
    global $DB;

    $results = $DB->Query("
    SELECT
    t.ID,  c.NAME cl, t.PRICE pr, s.NAME st 
    FROM px_task t
        JOIN px_task_status s ON t.STATUS_ID = s.ID
        JOIN px_client c ON t.CLIENT_ID = c.ID
    "); 

    $out_arr =  array();

    while($row = $results->Fetch()){ 
    $name = $row["cl"];
    $id = $row["ID"];
    $price = $row["pr"];
    $status = $row["st"]; 

    if (array_key_exists($name,$out_arr)){
        $out_arr[$name]["count"] ++;
        if($status == "Выполнено") {  
            $out_arr[$name]["Fst"] += (int)$price;
        }else{
            $out_arr[$name]["Pst"] += (int)$price;
        }
    }else{
        $out_arr[$name]["id"] = $id;
        $out_arr[$name]["count"] = (int)1;
        if($status == "Выполнено") {  
            $out_arr[$name]["Fst"] = (int)$price;
        }else{
            $out_arr[$name]["Pst"] = (int)$price;
        }
    }   
} 

?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Сумма выполненных задач</th>
            <th>Сумма задач которые в процессе</th>
            <th>Общее количество задач</th>
        </tr>
    </thead>
    <tbody>


        <?
foreach ($out_arr as $key => $value) {
?>

        <tr>
            <td><?=$value["id"];?></td>
            <td><?=$key;?></td>
            <td><?=$value["Fst"];?></td>
            <td><?=$value["Pst"] ;?></td>
            <td><?=$value["count"];?></td>

        </tr>
        <?
}
?>
    </tbody>
<table> 
        <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
