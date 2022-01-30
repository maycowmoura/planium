import { useEffect, useState } from 'react';
import './App.css';
import api from './api';
import SwitchComponents from './components/SwitchComponents';
import Loader from './components/Loader';
import RegisterPlans from './components/RegisterPlans';
import Budget from './components/Budget';

function App() {
  const [active, setActive] = useState('loadingPlans');
  const [data, setData] = useState({ plans: null, budget: null });
  const props = { data, setData, active, setActive };

  useEffect(() => {
    api.get('/plans')
      .then(resp => {
        if (resp.data.error) {
          alert('Ocorreu um erro. Consulte o console.');
          console.log(resp.data);
          return;
        }

        setData(data => ({ ...data, plans: resp.data }))
        setActive('register')
      })
      .catch(error => {
        alert('Erro na requisição.');
        console.log(error);
      })
  }, [])


  return (
    <div className="app container">
      <SwitchComponents active={active}>
        <Loader name="loadingPlans" text="Carregando planos..." />
        <RegisterPlans name="register" {...props} />
        <Loader name="loadingBudget" text="Preparando orçamento..." />
        <Budget name="budget" {...props} />
      </SwitchComponents>
    </div>
  );
}

export default App;
