<script type='text/javascript'>
    function addField(number) {

        container = document.getElementById("fields_container");

        tr = document.createElement("tr");
        tr.id = "p" + (number + 1);

        container.appendChild(tr);

        td1 = document.createElement("td");
        td2 = document.createElement("td");
        td3 = document.createElement("td");
        td4 = document.createElement("td");
        td5 = document.createElement("td");
        
        fieldName = document.createElement("input");
        fieldName.type = "text";
        fieldName.name = "fieldsname[]";

        urlParam = document.createElement("input");
        urlParam.type = "text";
        urlParam.name = "params[]";
        
        validationType = document.createElement("select");
        validationType.type = "text";
        validationType.name = "validations[]";

            option1 = document.createElement('option');
            option1.value = "0";
            option1.appendChild(document.createTextNode("No validation"));
            option2 = document.createElement('option');
            option2.value = "1";
            option2.appendChild(document.createTextNode("Mobile Phone"));
            option3 = document.createElement('option');
            option3.value = "2";
            option3.appendChild(document.createTextNode("E-mail"));
            option4 = document.createElement('option');
            option4.value = "3";
            option4.appendChild(document.createTextNode("Date"));

        validationType.appendChild(option1);
        validationType.appendChild(option2);
        validationType.appendChild(option3);
        validationType.appendChild(option4);

        td1.appendChild(document.createTextNode("Field"));
        td2.appendChild(fieldName);
        td3.appendChild(urlParam);
        td4.appendChild(validationType);


        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);

    }

    function deleteRow (row) {
        element = document.getElementById(row);
        element.parentNode.removeChild(element);
    }

</script>

<div class="wraper">
    <h2>Plugin CF7 Data to URL Settings</h2>

    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options');?>
    <?php settings_fields('cf7datasend');?>
        <table>

        <tbody id="fields_container">

            <tr>
                <td>
                    <h3>URL Settings</h3>
                </td>
            </tr>

            <tr>
                <td scope="row">Base URL to send data:</td>
                <td><input type="text" name="base_url" value="<?php echo get_option('base_url'); ?>" /></td>
            </tr>

            <tr>
                <td>
                    <h3>URL Settings</h3>
                </td>
            </tr>

            <tr>
                <td scope="row">Contact Form 7 to be watched:</td>
                <td>
                    <select name="form_id" id="form_id">
                        <option value="">-- SELECT FORM --</option>
                        <?php
                            $dbValue = get_option('form_id');
                            $posts = get_posts(array(
                                'post_type' => 'wpcf7_contact_form',
                                'numberposts' => -1,
                            ));
                            foreach ($posts as $p) {
                                echo '<option value="' . $p->ID . '"' . selected($p->ID, $dbValue, false) . '>' . $p->post_title . ' (' . $p->ID . ')</option>';
                            }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <h3>Fields</h3>
                </td>
                <td>
                    <?php
                        $fieldsNameCount = get_option('fieldsname');
                        $numberOfFields = sizeof($fieldsNameCount);
                    ?>
                    <input type="button" class="button-primary" value="<?php _e('Add new field')?>" onClick="javascript:addField(<?= $numberOfFields ?>)" />
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                <span>Contact Form 7 Field Name</span> 
                </td>
                <td>
                <span>URL Param</span> 
                </td>
                <td>
                <span>Validation type</span> 
                </td>
            </tr>

            <?php

                $fields = get_option('fieldsname');
                $params = get_option('params');
                $validations = get_option('validations');

                if(!empty($fields)){
                    foreach($fields as $key => $field) {
            ?>
            <tr id="<?= $key ?>">
                <td>Field</td>
                <td>
                    <input type="text" name="fieldsname[<?= $key ?>]" value="<?= $field ?>" />
                </td>
                <td>
                    <input type="text" name="params[<?= $key ?>]" value="<?= $params[$key] ?>" />
                </td>
                <td>
                    <select name="validations[<?= $key ?>]">
                            <option <?= ($validations[$key] == 0) ? "selected" : "" ?> value="0">No validation</option>
                            <option <?= ($validations[$key] == 1) ? "selected" : "" ?> value="1">Mobile Phone</option>
                            <option <?= ($validations[$key] == 2) ? "selected" : "" ?> value="2">E-mail</option>
                            <option <?= ($validations[$key] == 3) ? "selected" : "" ?> value="3">Date</option>
                    </select>
                </td>
                <td> <a href="#" onClick="javascript:deleteRow(<?= $key ?>)">delete</a> </td>
            </tr>
            <?php
                    }
                } else {
            
            ?>
            <tr>
                <td>Field</td>
                <td>
                    <input type="text" name="fieldsname[]" value="" />
                </td>
                <td>
                    <input type="text" name="params[]" value="" />
                </td>
                <td>
                    <select name="validations[]">
                            <option>No validation</option>
                            <option>Mobile Phone</option>
                            <option>E-mail</option>
                            <option>Date</option>
                    </select>
                </td>
            </tr>
            <?php
                }
            ?>

            

        </tbody>

        </table>

        <input type="hidden" name="action" value="update" />
        <input type="submit" class="button-primary" value="<?php _e('Save Changes')?>" />

    </form>
    
</div>