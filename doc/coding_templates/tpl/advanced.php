<?=tpl('assets/fragment')?>
This is the advanced page. Current execution order is:
<b><?=( isset( $this->DATA['execution_order_counter'] ) ? var_dump( $this->DATA['execution_order_counter'] ) : "Uninitialized" )?></b>
 ($this->DATA['execution_order_counter']);<br/>
<?=tpl('assets/fragment')?>
