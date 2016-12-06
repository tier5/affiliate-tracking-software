{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

{if isset($tier_enabled)}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$tlinks_title}</h1>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">

{if isset($forced_links)}
<div class="alert alert-warning">{$tlinks_forced_two}</div>
{else}
<div class="alert alert-warning">{$tlinks_embedded_two}</div>
<p><strong>{$tlinks_forced_code}</strong><br />{$tlinks_embedded_one}<br /><br /></p>
{/if}

{if isset($forced_links)}
<strong>{$tlinks_forced_code}</strong><br />
{if isset($seo_links)}
<textarea textarea rows="2" class="form-control"><a href="{$seo_url}signup-{$textads_link}{$textads_link_html_added}">{$tlinks_forced_money}</a></textarea>
{else}
<textarea textarea rows="2" class="form-control"><a href="{$base_url}/recruit.php?ref={$link_id}">{$tlinks_forced_money}</a></textarea>
{/if}
{$tlinks_forced_paste}<br /><br />
{/if}

<table class="table table-primary table-bordered">
<thead>
<tr>
<th width="25%"><b>{$tlinks_payout_structure}</b></th>
<th width="75%"><b>{$tlinks_active}: <span class="label label-danger">{$tier_numbers}</span></b></th>
</tr>
</thead>
<tbody>
{if isset($tier_1_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 1</strong></td>
      <td width="75%">{$tier_1_amount}{$tier_1_type}</td>
    </tr>
{/if}
{if isset($tier_2_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 2</strong></td>
      <td width="75%">{$tier_2_amount}{$tier_2_type}</td>
    </tr>
{/if}
{if isset($tier_3_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 3</strong></td>
      <td width="75%">{$tier_3_amount}{$tier_3_type}</td>
    </tr>
{/if}
{if isset($tier_4_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 4</strong></td>
      <td width="75%">{$tier_4_amount}{$tier_4_type}</td>
    </tr>
{/if}
{if isset($tier_5_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 5</strong></td>
      <td width="75%">{$tier_5_amount}{$tier_5_type}</td>
    </tr>
{/if}
{if isset($tier_6_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 6</strong></td>
      <td width="75%">{$tier_6_amount}{$tier_6_type}</td>
    </tr>
{/if}
{if isset($tier_7_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 7</strong></td>
      <td width="75%">{$tier_7_amount}{$tier_7_type}</td>
    </tr>
{/if}
{if isset($tier_8_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 8</strong></td>
      <td width="75%">{$tier_8_amount}{$tier_8_type}</td>
    </tr>
{/if}
{if isset($tier_9_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 9</strong></td>
      <td width="75%">{$tier_9_amount}{$tier_9_type}</td>
    </tr>
{/if}
{if isset($tier_10_active)}
    <tr>
      <td width="25%"><strong>{$tlinks_level} 10</strong></td>
      <td width="75%">{$tier_10_amount}{$tier_10_type}</td>
    </tr>
{/if}
</tbody>
</table>

</div>
</div>
</div>
</div>

{/if}