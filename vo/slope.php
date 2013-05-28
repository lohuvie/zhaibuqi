<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-28
 * Time: 下午2:25
 * To change this template use File | Settings | File Templates.
 */
//获取所有的该物品的平均差
$query = "select distinct r1.itemID,r2.ratingValue - r1.ratingValue as rating_difference
            from rating r1
            join rating r2 on r1.user_id = r2.user_id
            where r2.user_id = $user_id and r2.item_id = $item_id";
$result = mysqli_query($dbc,$query);
$nums_rows = mysqli_num_rows($result);
//对于所有该物品对的dev行 全部更新
while($row = mysqli_fetch_array($result,MYSQLI_BOTH)){
    $other_itemID = $row['itemID'];
    $rating_difference = $row['rating_difference'];
    //如果($itemID,$other_itemID)已经存在于dev表中，更新数据
    if (mysqli_num_rows(mysqli_query($dbc,"SELECT itemID1
    FROM dev WHERE itemID1=$itemID AND itemID2=$other_itemID")) > 0){
        $query = "update dev set
                    count = count+1,
                    sum = sum + $rating_difference
                    where itemID1 = $itemID and itemID2 = $other_itemID";
        $result = mysqli_query($dbc,$query);
        //如果两个物品不相同 则更新两行数据
        if ($itemID != $other_itemID) {
            $query = "UPDATE dev SET
                          count=count+1,
                          sum=sum-$rating_difference
                          WHERE (itemID1=$other_itemID AND itemID2=$itemID)";
            mysqli_query($dbc, $query);
        }
    }else{
        //如果不存在，插入数据
        $query = "INSERT INTO dev VALUES ($itemID, $other_itemID,1, $rating_difference)";
        mysqli_query($dbc, $query);
        //We only want to insert if the items are different
        if ($itemID != $other_itemID) {
            $query = "INSERT INTO dev VALUES ($other_itemID,$itemID, 1, -$rating_difference)";
            mysqli_query($dbc, $query);
        }
    }
}
