<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::factory()->create([
            'name' => 'Medmar Navi',
            'logo' => 'https://www.medmargroup.it/catalog/view/theme/default/image/BASE-logo-v.png',
            'vat' => '05984260637',
            'fiscal_code' => '05984260637',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'page_header' => 'Mail: g.niola@medmarnavi.it - acquisti@medmarnavi.it',
            'page_footer' => '<ul style="font-size:8px; text-align: justify;">
            <li> Ogni deroga alle condizioni generali e particolari indicate in ordine sarà valida soltanto se accettate per iscritto dall’Acquirente.</li>
            <li> L’ordine si intende perfezionato con il ricevimento da parte dell’acquirente della conferma integrale dello stesso, che dovrà pervenirci entro il 10° giorno dalla data dell’ordine, sottoscritta dal Fornitore per accettazione. Al riguardo si fa rinvio alle disposizioni di cui all’Art.1326 del Codice Civile.</li>
            <li> L’ordine integralmente accettato produce gli effetti previsti dalla legge.</li>
            <li> I termini di consegna s’intendono essenziali: il mancato rispetto degli stessi oltre a legittimare la richiesta di risarcimento ai sensi degli articoli 1218 e 1223 C.C. dà il diritto all’Acquirente di risolvere il contratto per inadempimento.</li>
            <li> Nelle spedizioni eseguite con qualsiasi mezzo di trasporto il Fornitore, o chi per lui, dovrà tempestivamente comunicare gli estremi di spedizione, il numero e la data dell’ordine.</li>
            <li> Le consegne eseguite non in accordo ai termini fissati saranno considerate, ai fini del pagamento, come avvenute nei termini più favorevoli all’Acquirente.</li>
            <li> Normalmente, se non sia stato convenuto altrimenti nella ordinazione, il costo dell’imballaggio non viene riconosciuto intendendosi compreso nel prezzo della merce. Come pure, a meno che sia stato convenuto differentemente nell’ordinazione, il prezzo pattuito si intende riferito al peso netto della merce escluso imballo. Avarie o dispersione della merce causate da imballaggio inidoneo o difettoso sono a carico del Fornitore, il quale è tenuto a provvedere all’imballaggio nel modo più conveniente ed economico.</li>
            <li> Le forniture debbono essere spedite all’indirizzo indicato dall’Acquirente e con le modalità riportate nell’ordine. Ogni maggiore onere derivante da inosservanza di questa modalità, sarà addebitato al fornitore.</li>
            <li> Le merci debbono esser accompagnate da una bolla di consegna sulla quale oltre alla descrizione della fornitura ed il numero di specifica dell’Acquirente, deve essere indicato il numero dell’ordine di acquisto.</li>
            <li> Ove richiesto, le merci debbono essere accompagnate dal Certificato di Conformità in due esemplari.</li>
            <li> Entro i termini e con le modalità previste dalle vigenti disposizioni, deve essere inviata all’Acquirente regolare fattura, indicante gli estremi della bolla di consegna e dell’ordine di acquisto.</li>
            <li> Le fatture non rispondenti alle norme di legge vigenti saranno restituite per le necessarie rettifiche e dovranno pervenire regolarizzate entro i termini previsti dal D.P.R. 26/10/72 n. 633. In caso contrario verrà emessa autofattura ai sensi dell’art. 41 del D.P.R. citato.</li>
            <li> Le merci contrassegnate all’Acquirente si intendono consegnate in deposito e custodia fino ad avvenuto controllo e conseguente accettazione.</li>
            <li> Il controllo qualitativo e quantitativo delle merci sarà effettuato dall’Acquirente, salvo casi particolari e previamente indicati nell’ordine. L’acquirente notificherà eventuali scarti entro 60 gg. dalla presa in consegna della merce.</li>
            <li> Il fornitore s’impegna ad accettare tutti i controlli che l’acquirente effettuerà sulle forniture al fine di stabilire la loro rispondenza alle caratteristiche previste dall’ordine.</li>
            <li> Le merci non accettate in seguito al controllo da parte dell’Acquirente saranno rispedite al Fornitore in porto assegnato. Le relative fatture potranno essere tenute in sospeso fino al reintegro oppure liquidate, a discrezione dell’Acquirente, per la quota parte accettata.</li>
            <li> Il Fornitore garantisce le proprie merci: in particolare che siano di ottima qualità, esenti da difetti, palesi e occulti, che rispondano a tutti i requisiti indicati nell’offerta o nell’ordine confermato.</li>
            <li> Con l’accettazione del presente ordine, si rinuncia espressamente al beneficio di cui D.Lgs 231/2002 riservandosi la facoltà di procedere, in caso di inadempimento, con specifica messa in mora.</li>
            <li> E’ tassativamente escluso il pagamento mediante cessione del credito (art. 1260 C.C.) nonché con procura irrevocabile (art. 1723 C.C.).</li>
            </ul>
            <hr style="margin:5px;" />
            <p style="font-size:12px; text-align: justify;">
            Società soggetta a direzione e coordinamento della Mediterranea Marittima S.p.A. <b>Sede Legale e Uffici:</b> Via Alcide De Gasperi, 55 - 80133 Napoli Tel. 081.5801223 - Fax 081.5512770 - Capitale Sociale € 6\'000\'000.00 int. vers. Codice Fiscale, Partita IVA e numero iscrizione Registro delle Imprese di Napoli 05984260637 R.E.A. 473782 - <b>Call Center:</b> Tel. 081.3334411 - info@medmarnavi.it - www.medmarnavi.it</p>
            ',

            'is_activated' => true,
        ]);

        Company::factory()->create([
            'name' => 'Rifim',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'is_activated' => true,
            'page_footer' => '<p style="font-size:8px; text-align: justify;">
            - Ogni deroga alle condizioni generali e particolari indicate in ordine sarà valida soltanto se accettate per iscritto dall’Acquirente.
            - L’ordine si intende perfezionato con il ricevimento da parte dell’acquirente della conferma integrale dello stesso, che dovrà pervenirci entro il 10° giorno dalla data dell’ordine, sottoscritta dal Fornitore per accettazione. Al riguardo si fa rinvio alle disposizioni di cui all’Art.1326 del Codice Civile.
            - L’ordine integralmente accettato produce gli effetti previsti dalla legge.
            - I termini di consegna s’intendono essenziali: il mancato rispetto degli stessi oltre a legittimare la richiesta di risarcimento ai sensi degli articoli 1218 e 1223 C.C. dà il diritto all’Acquirente di risolvere il contratto per inadempimento.
            - Nelle spedizioni eseguite con qualsiasi mezzo di trasporto il Fornitore, o chi per lui, dovrà tempestivamente comunicare gli estremi di spedizione, il numero e la data dell’ordine.
            - Le consegne eseguite non in accordo ai termini fissati saranno considerate, ai fini del pagamento, come avvenute nei termini più favorevoli all’Acquirente.
            - Normalmente, se non sia stato convenuto altrimenti nella ordinazione, il costo dell’imballaggio non viene riconosciuto intendendosi compreso nel prezzo della merce. Come pure, a meno che sia stato convenuto differentemente nell’ordinazione, il prezzo pattuito si intende riferito al peso netto della merce escluso imballo. Avarie o dispersione della merce causate da imballaggio inidoneo o difettoso sono a carico del Fornitore, il quale è tenuto a provvedere all’imballaggio nel modo più conveniente ed economico.
            - Le forniture debbono essere spedite all’indirizzo indicato dall’Acquirente e con le modalità riportate nell’ordine. Ogni maggiore onere derivante da inosservanza di questa modalità, sarà addebitato al fornitore.
            - Le merci debbono esser accompagnate da una bolla di consegna sulla quale oltre alla descrizione della fornitura ed il numero di specifica dell’Acquirente, deve essere indicato il numero dell’ordine di acquisto.
            - Ove richiesto, le merci debbono essere accompagnate dal Certificato di Conformità in due esemplari.
            - Entro i termini e con le modalità previste dalle vigenti disposizioni, deve essere inviata all’Acquirente regolare fattura, indicante gli estremi della bolla di consegna e dell’ordine di acquisto.
            - Le fatture non rispondenti alle norme di legge vigenti saranno restituite per le necessarie rettifiche e dovranno pervenire regolarizzate entro i termini previsti dal D.P.R. 26/10/72 n. 633. In caso contrario verrà emessa autofattura ai sensi dell’art. 41 del D.P.R. citato.
            - Le merci contrassegnate all’Acquirente si intendono consegnate in deposito e custodia fino ad avvenuto controllo e conseguente accettazione.
            - Il controllo qualitativo e quantitativo delle merci sarà effettuato dall’Acquirente, salvo casi particolari e previamente indicati nell’ordine. L’acquirente notificherà eventuali scarti entro 60 gg. dalla presa in consegna della merce.
            - Il fornitore s’impegna ad accettare tutti i controlli che l’acquirente effettuerà sulle forniture al fine di stabilire la loro rispondenza alle caratteristiche previste dall’ordine.
            - Le merci non accettate in seguito al controllo da parte dell’Acquirente saranno rispedite al Fornitore in porto assegnato. Le relative fatture potranno essere tenute in sospeso fino al reintegro oppure liquidate, a discrezione dell’Acquirente, per la quota parte accettata.
            - Il Fornitore garantisce le proprie merci: in particolare che siano di ottima qualità, esenti da difetti, palesi e occulti, che rispondano a tutti i requisiti indicati nell’offerta o nell’ordine confermato.
            - Con l’accettazione del presente ordine, si rinuncia espressamente al beneficio di cui D.Lgs 231/2002 riservandosi la facoltà di procedere, in caso di inadempimento, con specifica messa in mora.
            - E’ tassativamente escluso il pagamento mediante cessione del credito (art. 1260 C.C.) nonché con procura irrevocabile (art. 1723 C.C.).
            <hr style="margin:5px;" />
            <p style="font-size:10px; text-align: justify;">Società soggetta a direzione e coordinamento della Mediterranea Marittima S.p.A. <b>Sede Legale e Uffici:</b> Via Alcide De Gasperi, 55 - 80133 Napoli Tel. 081.5801223 - Fax 081.5512770 - Capitale Sociale € 6\'000\'000.00 int. vers. Codice Fiscale, Partita IVA e numero iscrizione Registro delle Imprese di Napoli 05984260637 R.E.A. 473782 - <b>Call Center:</b> Tel. 081.3334411 - info@medmarnavi.it - www.medmarnavi.it</p>',
        ]);

        Company::factory()->create([
            'name' => 'Mediterranea Marittima',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'is_activated' => true,
        ]);
    }
}
