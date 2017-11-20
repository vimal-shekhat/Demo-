<table border="0" bgcolor="#f3f3f3" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td height="42"></td>
        </tr>
        <tr>
            <td>
                <table border="0" bgcolor="#ffffff" align="center" width="600" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td bgcolor="#d6d6d6" height="1" style="line-height:1px" colspan="3"></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d6d6d6" width="1"></td>
                            <td>
                                <table border="0" align="center" width="598" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="12" colspan="1"></td>
                                        <td width="155" colspan="2" style="text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:20px;font-weight:bold;color:#203285"><img alt="<?php echo $this->request->data['message_to']; ?>" src="<?php echo PROJECT_URL; ?>img/<?php echo $this->request->data['message_to']; ?>_logo.png" style="float: left; width: 175px; padding-right: 19px; padding-top: 10px;"></td>
                                        <td width="100"></td>
                                    </tr>
                                </table>
                                <table border="0" align="center" width="598" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td height="0" colspan="4"></td>
                                        </tr>

                                        <tr>
                                            <td height="10" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#d6d6d6" height="1" style="line-height:1px" colspan="5"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#ffffff" height="1" style="line-height:1px" colspan="4"></td>
                                        </tr>  
                                        <tr>
                                            <td height="21" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td width="12"></td>
                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#666666" colspan="3"><b>Dear <?php echo $this->request->data['user_name']; ?>,</b></td>
                                        </tr>
                                        <tr>
                                            <td height="8" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td width="12"></td>
                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#666666;line-height:25px" colspan="3"> 
                                                <?php echo $this->request->data['message']; ?>
                                            </td>
                                            <td width="12"></td>
                                        </tr>
                                        <tr>
                                            <td height="32" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#d6d6d6" height="1" style="line-height:1px" colspan="5"></td>
                                        </tr>
                                        <?php if (!isset($this->request->data['test_email'])) { ?>
                                            <tr>
                                                <td bgcolor="#f3f3f3" height="10" colspan="5"></td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#f3f3f3" colspan="5">
                                                    <table border="0" align="center" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td width="12"></td>
                                                                <td bgcolor="#f3f3f3" style="font-family:Arial,Helvetica,sans-serif;font-size:9px;color:#666666;line-height:13px"><?php echo $this->request->data['unsubscribe_msg']; ?></td>                                                            
                                                                <td width="12"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#f3f3f3" height="10" colspan="5"></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td bgcolor="#d6d6d6" width="1"></td>
                        </tr>
                        <tr>
                            <td bgcolor="#d6d6d6" height="1" style="line-height:1px" colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td height="42"></td>
        </tr>
    </tbody>
</table>
