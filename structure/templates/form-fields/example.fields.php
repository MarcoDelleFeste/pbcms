<?php 
$this->fields = array(
	'nome'=>'',
	'cognome'=>'',
	'data_nascita'=>'',
	'codice_fiscale'=>'',
	'partita_iva'=>'',
	'indirizzo'=>'',
	'civico'=>'',
	'citta'=>'',
	'provincia'=>'',
	'nazione'=>'',
	'cap'=>'',
	'telefono_ufficio'=>'',
	'telefono_cellulare'=>'',
	'iscrizione_albi'=>'',
	'rete_appartenenza'=>'',
	'esperienza_lavorativa'=>'',
	'portafoglio'=>'',
	'numero_clienti'=>'',
	'privacy'=>'',
);
/*Iscrizioni agli albi professionali* (lista albi con check - IN ATTESA DI VALORI DAL CLIENTE)

Portafoglio medio -> DIVENTA: Portafoglio medio (mln di euro) -  (tendina con range - valori da 1 a 100 milioni + valore  "oltre 100")
*/


$this->fields_attrs['nome']['is_required'] = true;
$this->fields_attrs['nome']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['nome']['match_type'] = 'string';
$this->fields_attrs['nome']['label_name'] = 'Nome';
$this->fields_attrs['nome']['warning'] = $this->msg['nome'];

$this->fields_attrs['cognome']['is_required'] = true;
$this->fields_attrs['cognome']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['cognome']['match_type'] = 'string';
$this->fields_attrs['cognome']['label_name'] = 'Cognome';
$this->fields_attrs['cognome']['warning'] = $this->msg['cognome'];

$this->fields_attrs['data_nascita']['is_required'] = true;
$this->fields_attrs['data_nascita']['htm'] = array('tag'=>'input', 'type'=>'text', 'option'=>array('type'=>'calendar', 'start'=>mktime(0,0,0,1,1,1920)));
$this->fields_attrs['data_nascita']['match_type'] = 'string';
$this->fields_attrs['data_nascita']['label_name'] = 'Data di nascita';
$this->fields_attrs['data_nascita']['warning'] = $this->msg['nome'];

$this->fields_attrs['codice_fiscale']['is_required'] = true;
$this->fields_attrs['codice_fiscale']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['codice_fiscale']['match_type'] = 'string';
$this->fields_attrs['codice_fiscale']['label_name'] = 'Codice Fiscale';
$this->fields_attrs['codice_fiscale']['warning'] = $this->msg['nome'];

$this->fields_attrs['partita_iva']['is_required'] = true;
$this->fields_attrs['partita_iva']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['partita_iva']['match_type'] = 'string';
$this->fields_attrs['partita_iva']['label_name'] = 'Partita Iva';
$this->fields_attrs['partita_iva']['warning'] = $this->msg['nome'];

$this->fields_attrs['indirizzo']['is_required'] = true;
$this->fields_attrs['indirizzo']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['indirizzo']['match_type'] = 'string';
$this->fields_attrs['indirizzo']['label_name'] = 'Indirizzo';
$this->fields_attrs['indirizzo']['warning'] = $this->msg['nome'];

$this->fields_attrs['civico']['is_required'] = true;
$this->fields_attrs['civico']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['civico']['match_type'] = 'string';
$this->fields_attrs['civico']['label_name'] = 'Nr&deg; Civico';
$this->fields_attrs['civico']['warning'] = $this->msg['nome'];

$this->fields_attrs['citta']['is_required'] = true;
$this->fields_attrs['citta']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['citta']['match_type'] = 'string';
$this->fields_attrs['citta']['label_name'] = 'Citt&agrave;';
$this->fields_attrs['citta']['warning'] = $this->msg['nome'];

$this->fields_attrs['provincia']['is_required'] = true;
$this->fields_attrs['provincia']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['provincia']['match_type'] = 'string';
$this->fields_attrs['provincia']['label_name'] = 'Provincia';
$this->fields_attrs['provincia']['warning'] = $this->msg['nome'];

$this->fields_attrs['nazione']['is_required'] = true;
$this->fields_attrs['nazione']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['nazione']['match_type'] = 'string';
$this->fields_attrs['nazione']['label_name'] = 'Nazione';
$this->fields_attrs['nazione']['warning'] = $this->msg['nazione'];

$this->fields_attrs['cap']['is_required'] = true;
$this->fields_attrs['cap']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['cap']['match_type'] = 'string';
$this->fields_attrs['cap']['label_name'] = 'Cap';
$this->fields_attrs['cap']['warning'] = $this->msg['cap'];

$this->fields_attrs['telefono_ufficio']['is_required'] = false;
$this->fields_attrs['telefono_ufficio']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['telefono_ufficio']['match_type'] = 'string';
$this->fields_attrs['telefono_ufficio']['label_name'] = 'Telefono (ufficio)';
$this->fields_attrs['telefono_ufficio']['warning'] = $this->msg['telefono_ufficio'];

$this->fields_attrs['telefono_cellulare']['is_required'] = true;
$this->fields_attrs['telefono_cellulare']['htm'] = array('tag'=>'input', 'type'=>'text');
$this->fields_attrs['telefono_cellulare']['match_type'] = 'string';
$this->fields_attrs['telefono_cellulare']['label_name'] = 'Telefono (cellulare)';
$this->fields_attrs['telefono_cellulare']['warning'] = $this->msg['telefono_cellulare'];

$this->fields_attrs['iscrizione_albi']['is_required'] = true;
$this->fields_attrs['iscrizione_albi']['htm'] =  array('tag'=>'input', 'type'=>'checkbox', 'set_checked'=>true, 'count'=>3, 'values'=>array(1=>0, 2=>1, 3=>2), 'labels'=>array(1=>'albo 1', 2=>'albo 2', 3=>'albo 4'));
$this->fields_attrs['iscrizione_albi']['match_type'] = 'string';
$this->fields_attrs['iscrizione_albi']['label_name'] = 'Iscrizioni agli albi professionali';
$this->fields_attrs['iscrizione_albi']['warning'] = $this->msg['iscrizione_albi'];

$this->fields_attrs['rete_appartenenza']['is_required'] = true;
$this->fields_attrs['rete_appartenenza']['htm'] = array('tag'=>'select','option'=>array('type'=>'list', 'values'=>
																						array(
																						    'ALLIANZ BANK FINANCIAL ADVISORS S.p.A.',
																							'ALTO ADIGE BANCA S.p.A.',
																							'ANIMA SGR S.p.A.',
																							'APOGEO CONSULTING SIM S.p.A.',
																							'AZ INVESTIMENTI SIM S.p.A.',
																							'AZIMUT CONSULENZA PER INVESTIMENTI SIM S.p.A.',
																							'BANCA EUROMOBILIARE S.p.A.',
																							'BANCA FIDEURAM S.p.A.',
																							'BANCA GENERALI S.p.A.',
																							'BANCA IPIBI FINANCIAL ADVISORY S.p.A.',
																							'BANCA MEDIOLANUM S.p.A.',
																							'BANCA MONTE DEI PASCHI DI SIENA S.p.A.',
																							'BANCA NUOVA S.p.A.',
																							'BANCA PATRIMONI SELLA & C. S.p.A.',
																							'BANCA POPOLARE DI BARI S.c.p.A.',
																							'BANCA POPOLARE DI MILANO S.c.r.l.',
																							'BANCA POPOLARE DI PUGLIA E BASILICATA S.c.r.l.',
																							'BANCA POPOLARE DI SPOLETO S.p.A.',
																							'BANCA POPOLARE DI VICENZA S.c.p.a.r.l.',
																							'BANCA REALE S.p.A.',
																							'CONSULTINVEST INVESTIMENTI SIM S.p.A.',
																							'CREDITO EMILIANO S.p.A.',
																							'FIDELITY WORLDWIDE INVESTMENT',
																							'FINANZA & FUTURO BANCA S.p.A.',
																							'FINECOBANK S.p.A.',
																							'HYPO ALPE-ADRIA-BANK S.p.A.',
																							'HYPO TIROL BANK ITALIA S.p.A.',
																							'INVESCO ASSET MANAGEMENT',
																							'IW BANK S.p.A.',
																							'JPMORGAN ASSET MANAGEMENT (EUROPE) S.a.r.l.',
																							'SANPAOLO INVEST SIM S.p.A.',
																							'SCHRODERS ITALY SIM S.p.A.',
																							  )));
$this->fields_attrs['rete_appartenenza']['match_type'] = 'string';
$this->fields_attrs['rete_appartenenza']['label_name'] = 'Rete di appartenenza';
$this->fields_attrs['rete_appartenenza']['warning'] = $this->msg['rete_appartenenza'];

$this->fields_attrs['esperienza_lavorativa']['is_required'] = true;
$this->fields_attrs['esperienza_lavorativa']['htm'] = array('tag'=>'select','option'=>array('type'=>'values_list', 'start'=>1, 'end'=>80));
$this->fields_attrs['esperienza_lavorativa']['match_type'] = 'string';
$this->fields_attrs['esperienza_lavorativa']['label_name'] = 'Attivit&agrave; svolta da (anni)';
$this->fields_attrs['esperienza_lavorativa']['warning'] = $this->msg['esperienza_lavorativa'];

$this->fields_attrs['portafoglio']['is_required'] = false;
$valori = array();

for($m=1;$m<101;$m++)
{
	$valori[$m] = $m.' mln';
}
$valori[101] = 'oltre 100 mln';
$this->fields_attrs['portafoglio']['htm'] = array('tag'=>'select','option'=>array('type'=>'value_label', 'values'=>$valori));
$this->fields_attrs['portafoglio']['match_type'] = 'num';
$this->fields_attrs['portafoglio']['label_name'] = 'Portafoglio medio (mln di euro)';
$this->fields_attrs['portafoglio']['warning'] = $this->msg['portafoglio'];

$this->fields_attrs['numero_clienti']['is_required'] = true;
$this->fields_attrs['numero_clienti']['htm'] = array('tag'=>'select','option'=>array('type'=>'list', 'values'=>
																						array(
																						    '0-50',
																							'50-100',
																							'100-200',
																							'200-400',
																							'oltre 400',
																							  )));
$this->fields_attrs['numero_clienti']['match_type'] = 'string';
$this->fields_attrs['numero_clienti']['label_name'] = 'Numero di clienti';
$this->fields_attrs['numero_clienti']['warning'] = $this->msg['numero_clienti'];

$this->fields_attrs['privacy']['is_required'] = true;
$this->fields_attrs['privacy']['htm'] = array('tag'=>'input', 'type'=>'radio', 'set_checked'=>false, 'count'=>2, 'values'=>array(1=>0, 2=>1), 'labels'=>array(1=>'No', 2=>'Si'));
$this->fields_attrs['privacy']['match_type'] = 'bool';
$this->fields_attrs['privacy']['label_name'] = 'Privacy';
$this->fields_attrs['privacy']['warning'] = $this->msg['privacy'];

