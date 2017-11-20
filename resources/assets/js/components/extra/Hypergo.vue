<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Hypergeometric Calculator</div>

                    <div class="panel-body">

                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Population size:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"  placeholder="e.g. Number of cards remaining in your deck" v-model="populationSize">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Number of successes in population</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"  placeholder="e.g. Number of cards left in your deck" v-model="successPopulation" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Sample size</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"  placeholder="e.g. Number of draws" v-model="sampleSize" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-4" >Number of successes in population</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"  placeholder="Number of cards you need to draw" v-model="successSample" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary" @click.prevent="calculate()">Calculate</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Hypergeometric Probability P(X = {{sample}}) %	</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"   v-model="hypergeometricProbability" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Cumulative Probability: P(X < {{sample}}) % </label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"   v-model="CumulativeXsmall" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Cumulative Probability:P(X &le; {{sample}}) %</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"   v-model="CumulativeXsmallEquals" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Cumulative Probability: P(X > {{sample}}) %</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"   v-model="CumulativeXlarge" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" >Cumulative Probability: P(X &ge; {{sample}}) %</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control"   v-model="CumulativeXlargeEquals" >
                                </div>
                            </div>


                        </form>

                    </div>


                </div>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        data(){
            return {
                populationSize:null,
                successPopulation:null,
                sampleSize:null,
                successSample:null,
                hypergeometricProbability:null,
                CumulativeXsmall:null,
                CumulativeXsmallEquals:null,
                CumulativeXlarge:null,
                CumulativeXlargeEquals:null,
                sample:'x',
                root:null,
            }
        },
        methods: {
            calculate(){
                if(this.populationSize==null)
                {
                    this.root.showsuccess("Insert population size")
                    return;
                }

                if(this.successPopulation==null)
                {this.root.showsuccess("Insert success population size")
                    return;
                }
                if(this.sampleSize==null)
                {
                    this.root.showsuccess("Insert sample size")
                    return;
                }

                if(this.successSample==null){
                    this.root.showsuccess("Insert success sample size")
                    return;
                }


                let N=Number(this.populationSize); // PopulationSize(N)
                let k=Number(this.successPopulation);// SuccessPopulation(k)
                let n=Number(this.sampleSize);   // SampleSize(n)
                let x=Number(this.successSample); //successSample(x)


                this.hypergeometricProbability=this.decToPercent(this.hyperGoCalculation(x,N,n,k).toFixed(3));
                let cumulativeSmall=0;
                let cumulativeSmallEquals=0;


                for(let i=0;i<x;i++){
                    cumulativeSmall =cumulativeSmall+ this.hyperGoCalculation(i,N,n,k)
                }
                for(let i=0;i<=x;i++){
                    cumulativeSmallEquals =cumulativeSmallEquals+ this.hyperGoCalculation(i,N,n,k)
                }

                this.CumulativeXsmall=this.decToPercent(cumulativeSmall.toFixed(3));

                this.CumulativeXsmallEquals=this.decToPercent(cumulativeSmallEquals.toFixed(3)) ;

                let CumulativeXlargeEquals=1-cumulativeSmall.toFixed(3) ;
                 this.CumulativeXlargeEquals=this.decToPercent(CumulativeXlargeEquals.toFixed(3));
//                 this.CumulativeXlargeEquals=this.decToPercent(0.5);


                let CumulativeXlarge=1-cumulativeSmallEquals.toFixed(3) ;
                this.CumulativeXlarge=this.decToPercent(CumulativeXlarge.toFixed(3)) ;

                this.sample=this.successSample;


            },
            factorial(x){
                if(x===1||x===0){
                    return 1
                }else{
                    return x*this.factorial(x-1);
                }
            },
    binomial(n,k){
//
//        if (!Number.isInteger(n) || !Number.isInteger(k)) {
//            return false;
//        }

let returnBinomial= this.factorial(n) / (this.factorial(k) * this.factorial(n-k));


return returnBinomial;
    },

    hyperGoCalculation(x,N,n,k){
        let a=N-k;
        let b=n-x;
//        let a=x-m;
//        let b=n-k;

        return (this.binomial(k,x)*this.binomial(a,b))/this.binomial(N,n);

    },
            decToPercent(decimal){
        return (decimal*100).toFixed(2);
            }

        },
        mounted() {

        this.root=this.$root.$root;
        }
    }
</script>
