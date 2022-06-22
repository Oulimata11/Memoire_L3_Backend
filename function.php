<?php

function dynamicInsert($table_name, $assoc_array)
{
  $keys = array();
  $values = array();
  foreach ($assoc_array as $key => $value) {
    $keys[] = $key;
    $values[] = addslashes($value);
  }
  return "INSERT INTO $table_name(`" . implode("`,`", $keys) . "`) VALUES('" . implode("','", $values) . "')";
}

function dynamicUpdate($table_name, $assoc_array, $condition)
{
  $keyEgalValue = array();
  foreach ($assoc_array as $key => $value) {
    $keyEgalValue[] = addslashes($key) . " = '" . addslashes($value) . "'";
  }
  return "update $table_name set " . implode(",", $keyEgalValue) . " " . $condition;
}

function dynamicCondition($assoc_array, $operateur)
{
  if (count($assoc_array) == 0) {
    return "";
  }
  $keyOperateurValue = array();
  foreach ($assoc_array as $key => $value) {
    $keyOperateurValue[] = addslashes($key) . " " . $operateur . " '" . addslashes($value) . "'";
  }
  return "where " . implode(" and ", $keyOperateurValue);
}
function mode($mode_deploiement)
{
  if ($mode_deploiement) {
    echo "<h1>Mode déploiement activé</h1>";
    exit;
  }
}
function get_url()
{
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
  else
    $url = "http://";
  // Append the host(domain name, ip) to the URL.   
  $url .= $_SERVER['HTTP_HOST'];

  // Append the requested resource location to the URL   
  $url .= $_SERVER['REQUEST_URI'];

  return $url;
}
function get_exemple($table_name)
{
  return '
    <div class="row position-relative my-5">
      <div id="get_exemple" class="col-12">
        get_' . $table_name . '(){
          let api_url="' . get_url() . '/get";   //recevoir tout
          //let api_url="' . get_url() . '/get?id_' . $table_name . '=1"; // recevoir le(a) ' . $table_name . ' d\'identifiant 1

          this.http.get(api_url).subscribe((reponse:any)=>{
              //when success
              if(reponse.status){
                  console.log("Opération effectuée avec succés sur la table ' . $table_name . '. Réponse= ",reponse);
              }else{
                  console.log("L\'opération sur la table ' . $table_name . ' a échoué. Réponse= ",reponse);
              }
          },
          (error:any)=>{
              //when error
              console.log("Erreur inconnue! ",error);
          })
        }
      </div>
    </div>
  ';
}

function getParamsForAdd($description){
  $keysValues = array();
  foreach ($description as $key => $value) {
    if ($value["Key"] == "PRI") { //cle primaire
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (primary key)'";
    } else {
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . "'";
    }
  }
  $ts_object = implode(",\n\t\t", $keysValues);
  return <<<HTML
    <div id="json_add" class="col-12">
      {
        $ts_object
      }
    </div>
HTML;
}

function add_exemple($table_name)
{
  return '
    <div class="row position-relative my-5">
      <div id="add_exemple" class="col-12">
        add_' . $table_name . '(' . $table_name . ': any){
          this.loading_add_' . $table_name.'=true;
          //transformation des parametres à envoyer
          let formdata = new FormData()
          for (const key in ' . $table_name . ') {
            formdata.append(key, ' . $table_name . '[key])
          }
      
          let api_url = "' . get_url() . '/add" 
          this.http.post(api_url, formdata).subscribe((reponse: any) => {
            this.loading_add_' . $table_name.'=false;
            //when success
            if(reponse.status){
              console.log("Opération effectuée avec succés sur la table ' . $table_name . '. Réponse = ",reponse)
            }else{
              console.log("L\'opération sur la table ' . $table_name . ' a échoué. Réponse = ",reponse)
            }
          },
          (error: any) => {
            this.loading_add_' . $table_name.'=false;
            //when error
            console.log("Erreur inconnue! ", error)
          })
        }
      </div>
    </div>
  ';
}

function add_form($table_name,$description){
  $keysValues = array();
  foreach ($description as $key => $value) {
    if ($value["Key"] == "PRI") { //cle primaire
      // rien avec la cle primaire
    } else {
      $cle=$value["Field"];
      $type='text';

      $keysValues[] = '
      &lt;!-- '.$cle.' field avec un control de validite --&gt;
      &lt;div class="form-group"&gt;
        &lt;label >'.$cle.'&lt;/label&gt;
        &lt;input class="form-control" type="'.$type.'"  formControlName="'.$cle.'" class="form-control" [ngClass]="{ \'is-invalid\': submitted && f.'.$cle.'.errors }"/&gt;
        &lt;div *ngIf="submitted && f.firstName.errors" class="invalid-feedback"&gt;
            &lt;div *ngIf="f.firstName.errors.required"&gt; '.$cle.' est obligatoire &lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;';
    }
  }
  $content = implode("\t\t", $keysValues);
  return '
  <div class="row position-relative my-5">
    <div id="add_form" class="col-12">
      &lt;form  [formGroup]="reactiveForm_add_'.$table_name.'" (ngSubmit)="onSubmit_add_'.$table_name.'()" #form_add_'.$table_name.'="ngForm" &gt;
        '.$content.'
      &lt;/form&gt;
      &lt;!-- vous pouvez valider votre formulaire n\'importe ou --&gt;
      &lt;button type="button" class="btn btn-primary" (click)="form_add_'.$table_name.'.ngSubmit.emit()"&gt;Valider&lt;/button&gt;
      &lt;button class="btn btn-secondary" type="reset" (click)="onReset_add_'.$table_name.'()"&gt;Vider&lt;/button&gt;
    </div>
  </div>
  ';
}

function add_form_ts($table_name, $description)
{
  $keysValues = array();
  foreach ($description as $key => $value) {
    if ($value["Key"] == "PRI") { //cle primaire
      // rien avec la cle primaire
    } else {
      $cle = $value["Field"];
      $type = 'text';

      $keysValues[] = '
      ' . $cle . ': ["", Validators.required]';
    }
  }
  $content = implode(",", $keysValues);
  return '
  <div class="row position-relative my-5">
    <div id="add_form_ts" class="col-12">
      reactiveForm_add_' . $table_name . '!: FormGroup;
      submitted:boolean=false
      loading_add_' . $table_name . ':boolean=false
      constructor(private formBuilder: FormBuilder,public api:ApiService, private http:HttpClient) { }

      ngOnInit(): void {
        this.init_form()
      }
      init_form() {
          this.reactiveForm_add_' . $table_name . ' = this.formBuilder.group({
            ' . $content . '
          });
      }
  
      // acces facile au champs de votre formulaire
      get f(): any { return this.reactiveForm_add_' . $table_name . '.controls; }
      // validation du formulaire
      onSubmit_add_' . $table_name . '() {
        this.submitted = true;
        console.log(this.reactiveForm_add_' . $table_name . '.value)
        // stop here if form is invalid
        if (this.reactiveForm_add_' . $table_name . '.invalid) {
          return;
        }
        var ' . $table_name . '=this.reactiveForm_add_' . $table_name . '.value
        this.add_' . $table_name . '(' . $table_name . ')
      }
      // vider le formulaire
      onReset_add_' . $table_name . '() {
        this.submitted = false;
        this.reactiveForm_add_' . $table_name . '.reset();
      }
      </div>
    </div>
  ';
}
function getParamsForEdit($description){
  $keysValues = array();
  foreach ($description as $key => $value) {
    if ($value["Key"] == "PRI") { //cle primaire
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (primary key, obligatoire)'";
    } else if ($value["Key"] == "MUL") { //cle primaire
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (primary étrangère, obligatoire)'";
    } else if ($value["Null"] == "NO") { //cle primaire
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (obligatoire)'";
    } else {
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (facultatif)'";
    }
  }
  $ts_object = implode(",\n\t\t", $keysValues);
  return <<<HTML
    <div id="json_edit" class="col-12">
      {
        $ts_object
      }
    </div>
HTML;
}

function edit_exemple($table_name)
{
  return '
    <div class="row position-relative my-5">
      <div id="edit_exemple" class="col-12">
        edit_' . $table_name . '(' . $table_name . ': any){
          //transformation des parametres à envoyer
          let formdata = new FormData()
          for (const key in ' . $table_name . ') {
            formdata.append(key, ' . $table_name . '[key])
          }
      
          let api_url = "' . get_url() . '/edit" 
          this.http.post(api_url, formdata).subscribe((reponse: any)=>{
            //when success
            if(reponse.status){
              console.log("Opération effectuée avec succés sur la table ' . $table_name . '. Réponse = ",reponse)
            }else{
              console.log("L\'opération sur la table ' . $table_name . ' a échoué. Réponse = ",reponse)
            }
          },
          (error: any)=>{
            //when error
            console.log("Erreur inconnue! ",error)
          })
        }
      </div>
    </div>
  ';
}

function getParamsForDelete($description){
  foreach ($description as $key => $value) {
    if ($value["Key"] == "PRI") { //cle primaire
      $keysValues[] = $value["Field"] . ":'" . $value["Type"] . " (primary key, obligatoire)'";
    }
  }
  $ts_object = implode(",\n\t\t", $keysValues);
  return <<<HTML
    <div id="json_delete" class="col-12">
      {
        $ts_object
      }
    </div>
HTML;
}

function delete_exemple($table_name)
{
  return '
    <div class="row position-relative my-5">
      <div id="delete_exemple" class="col-12">
        delete_' . $table_name . '(' . $table_name . ': any){
          //transformation des parametres à envoyer
          let formdata = new FormData()
          for (const key in ' . $table_name . ') {
            formdata.append(key, ' . $table_name . '[key])
          }
      
          let api_url="' . get_url() . 'delete" 
          this.http.post(api_url, formdata).subscribe((reponse: any)=>{
            //when success
            if(reponse.status){
              console.log("Opération effectuée avec succés sur la table ' . $table_name . '. Réponse = ",reponse)
            }else{
              console.log("L\'opération sur la table ' . $table_name . ' a échoué. Réponse = ",reponse)
            }
          },
          (error: any)=>{
            //when error
            console.log("Erreur inconnue! ",error)
          })
        }
      </div>
    </div>
  ';
}
function table_documentation($table_name, $description)
{
  $dir    = './';
  $files = scandir($dir);
  foreach ($files as $key => $value) {
    if ($value != "." && $value != ".."  && $value != "index.php"  && $value != "config.php") {
      $action = str_replace(".php", "", $value);
      echo "<li class=\"fs-2\">$action <a href=\"./$action\"> --------> voir exemple</a></li>";
      if ($action == "get") {
        $code = get_exemple($table_name);
        echo <<<HTML
          <div class="row my-5">
            <p class="col-12 text-justify fs-4">
              L'action get permet d'obtenir des données de votre table <span class="text-danger">$table_name</span>, grâce 
              à la fonction ci-dessous vous pouvez récupérer une donnée spécifique ou toutes les données.
            </p>
          </div>
          $code
HTML;
      } else if ($action == "add") {
        $data = getParamsForAdd($description);
        $code = add_exemple($table_name);
        $add_form_code=add_form($table_name,$description);
        $add_form_ts_code = add_form_ts($table_name, $description);
        echo <<<HTML
          <div class="row my-5">
            <p class="col-12 text-justify fs-4">
              L'action add permet d'ajouter des données dans votre table <span class="text-danger">$table_name</span>, grâce 
              à la fonction ci-dessous vous pouvez ajouter des lignes dans votre table. <br>
              Cette fonction prend en paramètre un objet dont les clés correspondent aux attributs de la table $table_name dont 
              vous souhaitez affecter une nouvelle valeur.
            </p>
            $data
          </div>
          $code
          <div class="row my-5">
            <p class="col-12 text-justify fs-4">
              Pour ajouter ainsi une nouvelle ligne dans la table <span class="text-danger">$table_name</span>, c'est simple.
              Fini les longs formulaire à coder à la main, tout est généré automatiquement pour vous simplifiez la vie. <br>
              Vous n'avez qu'à faire nous faire confiance en copiant le code ci-dessous et le mettre au bon endroit dans votre projet et le tour est joué.
            </p>
            $add_form_code
            <h6>TS</h6>
            $add_form_ts_code
          </div>
HTML;
      } else if ($action == "edit") {
        $data = getParamsForEdit($description);
        $code = edit_exemple($table_name, $description);
        echo <<<HTML
          <div class="row my-5">
            <p class="col-12 text-justify fs-4">
              L'action edit permet de modifier des données dans votre table <span class="text-danger">$table_name</span>, grâce 
              à la fonction ci-dessous vous pouvez modifier des lignes de votre table. <br>
              Cette fonction prend en paramètre un objet dont les clés correspondent aux attributs de la table $table_name dont vous
              souhaitez modifier.
            </p>
            $data
          </div>
          $code
HTML;
      } else if ($action == "delete") {
        $data = getParamsForDelete($description);
        $code = delete_exemple($table_name, $description);
        echo <<<HTML
          <div class="row my-5">
            <p class="col-12 text-justify fs-4">
              L'action delete permet de supprimer des données de votre table <span class="text-danger">$table_name</span>, grâce 
              à la fonction ci-dessous vous pouvez supprimer des lignes de votre table. <br>
              Cette fonction prend en paramètre un objet dont les clés correspondent aux attributs de la table $table_name et dont 
              les valeurs permettent de définir la condition de suppression d'une ligne de la table.
            </p>
            $data
          </div>
          $code
HTML;
      }
    }
  }
}
function icone_check($bool)
{
  if ($bool) {
    echo '<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBkPSJNMjAuMjg1IDJsLTExLjI4NSAxMS41NjctNS4yODYtNS4wMTEtMy43MTQgMy43MTYgOSA4LjcyOCAxNS0xNS4yODV6Ii8+PC9zdmc+">';
  }
}
