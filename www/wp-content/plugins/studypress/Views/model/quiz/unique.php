<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 24/03/2015
 * Time: 10:18
 */
?>
<div class="loading hide"></div>
<div class="alert alert-danger alert-dismissible hide" role="alert">
    <p><!-- Le message d'erreur --></p>
</div>

                <div class="form-group">
                    <label for="question"><?php $tr->_e("Question"); ?></label>
                    <input type="text" class="form-control" id="question" name="question" required="required" />
                </div>


                <div class="form-group">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th><?php $tr->_e("True"); ?></th>
                            <th><?php $tr->_e("Proposition"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="col-md-1">
                                <input type="radio" name="true[]"/>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="prop" name="prop[]"  />
                            </td>

                        </tr>
                        <tr>
                            <td class="col-md-1">
                                <input type="radio" name="true[]"/>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="prop" name="prop[]"/>
                            </td>

                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2"><button class="btn btn-success float-right" id="add-new-proposition"><?php $tr->_e("Add a proposition"); ?></button></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                    <input  type="hidden" name="type-qcm" value="unique" />