<?php
    echo "<br>jopa";
    echo "myTitle: ".$myTitle;
?>

<table>
    <tr>
        <?foreach ($fields as $field):?>
            <th>
                <?=$field;?>
            </th>
        <?endforeach;?>
    </tr>

    <?foreach ($content as $row):?>
        <tr>
            <?foreach ($row as $column):?>
                <td>
                    <?=$column;?>
                </td>
            <?endforeach;?>
        </tr>
    <?endforeach;?>

</table>